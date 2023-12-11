<?php

if ($argc > 0) {
    // Get the connection with the dabase
    $conn = connectToDatabase();

    // Get the News Sources
    $newsSources = fetchNewsSources($conn);

    // Array to save news
    $allNews = [];

    foreach ($newsSources as $source) {
        $news = [];
        $rss = isRssFeed("{$source['url']}");

        if ($rss) {
            $news['newsSourceId'] = $source['id'];
            $news['userId'] = $source['user_id'];
            $news['categoryId'] = $source['category_id'];

            if (isset($rss->channel) && isset($rss->channel->item)) {
                // Handle the rss feed format
                foreach ($rss->channel->item as $item) {
                    $news = processRssItem($item, $news);
                    $allNews[] = $news;
                }
            } elseif (isset($rss->entry)) {
                // Handle the Atom feed format
                foreach ($rss->entry as $entry) {
                    $news = processAtomEntry($entry, $news);
                    $allNews[] = $news;
                }
            } else {
                print("Invalid XML format: " . $source['url']);
            }
        }
    }

    // Deletes all the old news and old tags, to avoid the repeat
    deleteAllRecords($conn, 'news');
    deleteAllRecords($conn, 'tags');
    deleteAllRecords($conn, 'news_tags');

    // Insert in the database all the news in the each timeline of the users
    foreach ($allNews as $specificNews) {
        try {
            if ($specificNews['date'] !== null) {
                $specificNews['finalDate'] = convertDate($specificNews['date']);
            } else {
                $specificNews['finalDate'] = null;
            }
            $newsId  = insertNews($conn, $specificNews);

            if (isset($specificNews['tags'])) {
                $tagsIds = insertTags($conn, $specificNews['tags']);
                insertAssociateTagsWithNews($conn, $newsId, $tagsIds);
            }
        } catch (Exception $e) {
            // Handle the exception and continue with the next news
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }

    $conn->close();

    print("News included in the timeline of each user.");
}

/**
 * Establish a connection to the database.
 *
 * @return mysqli|false The database connection object or false if the connection fails.
 */
function connectToDatabase()
{
    return mysqli_connect('localhost', 'root', '', 'my_news_cover2');
}

/**
 * Fetch news sources from the database.
 *
 * @param mysqli $conn The database connection.
 * @return array Associative array containing news source data.
 */
function fetchNewsSources($conn)
{
    $sqlSelectNewsSources = "SELECT * FROM `news_sources`;";
    $result = $conn->query($sqlSelectNewsSources);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Check if the given URL points to a valid RSS feed.
 *
 * @param string $url The URL of the RSS feed.
 * @return SimpleXMLElement|false The RSS feed as a SimpleXMLElement object, or false for an invalid RSS Feed.
 */
function isRssFeed($url)
{
    try {
        $xml = simplexml_load_file($url);
        return $xml;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Processes an RSS item to extract relevant information.
 *
 * @param SimpleXMLElement $item The RSS item to process.
 * @param array $news An associative array to store extracted information.
 * @return array The updated array containing extracted information.
 */
function processRssItem($item, $news)
{
    // Extract title, link, and date from the RSS item
    $news['title'] = $item->title;
    $news['link'] = $item->link;
    $news['date'] = $item->pubDate ?? null;
    $description = $item->description;

    // If in the description has the image tag
    preg_match('/<img[^>]+>/', $description, $matches);
    $description = strip_tags($description);
    $news['description'] = mb_substr($description, 0, 200, 'UTF-8');

    $news['image'] = getImageUrl($item, $matches);
    if (isset($item->category)) {
        $news['tags'] = getTags($item);
    }

    return $news;
}

/**
 * Processes an Atom entry to extract relevant information.
 *
 * @param SimpleXMLElement $entry The Atom entry to process.
 * @param array $news An associative array to store extracted information.
 * @return array The updated array containing extracted information.
 */
function processAtomEntry($entry, $news)
{
    // Extract title, link, date and summary (description) from the Atom entry
    $news['title'] = $entry->title;
    $news['link'] = $entry->link['href'];
    $news['date'] = $entry->published ?? null;
    $summary = $entry->summary;

    $summary = strip_tags($summary);
    $news['description'] = mb_substr($summary, 0, 200, 'UTF-8');

    // Access media:thumbnail directly
    $media = $entry->children('media', true);
    $thumbnail = $media->thumbnail;

    // Check if media:thumbnail is present and retrieve the image URL
    if ($thumbnail) {
        $news['image'] = (string)$thumbnail->attributes()['url'];
    } else {
        $news['image'] = '';
    }

    return $news;
}

/**
 * Delete all records from the specified table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $table The name of the table from which to delete records.
 */
function deleteAllRecords($conn, $table)
{
    $sql = "DELETE FROM `" . $table . "`;";
    mysqli_query($conn, $sql);
}

/**
 * Get the image URL from an RSS item.
 *
 * @param SimpleXMLElement $item The RSS item.
 * @param array $matches An array of matches from a regular expression.
 * @return string The image URL or an empty string if not found.
 */
function getImageUrl($item, $matches)
{
    $image_url = '';

    if (isset($item->enclosure)) {
        // Found the url image in the tag enclosure 
        if (isset($item->enclosure['url'])) {
            $image_url = (string)$item->enclosure['url'];
        } elseif (isset($item->enclosure[0])) {
            $image_url = trim((string)$item->enclosure[0]);
        }
    } elseif (isset($item->image)) {
        // Found the image element
        $image_url = (string)$item->image;
    } elseif (isset($item->children('media', true)->content)) {
        // Found the url image in the media:content
        if (isset($item->children('media', true)->content->thumbnail)) {
            $image_url = (string)$item->children('media', true)->content->thumbnail->attributes()['url'];
        } else {
            $image_url = (string)$item->children('media', true)->content->attributes()['url'];
        }
    } elseif (isset($matches[0])) {
        // Found the url image in the html tag in the description element
        if (preg_match('/src="([^"]+)"/', $matches[0], $imgMatches)) {
            $image_url = $imgMatches[1];
        }
    } elseif ($item->children('media', true)->group) {
        // Found the media:group element
        $firstMedia = $item->children('media', true)->group->content[0]->attributes()['url'];
        if ($firstMedia) {
            $image_url = (string)$firstMedia;
        }
    } elseif (isset($item->img['src'])) {
        $image_url = (string)$item->img['src'];
    }

    return $image_url;
}

/**
 * Convert a date string to the specified format.
 *
 * @param string $dateString The date string to convert.
 * @return string The formatted date.
 */
function convertDate($dateString)
{
    // Extract timezone information from the $dateString
    preg_match('/([+\-]\d{2})(\d{2})$/', $dateString, $matches);

    // Check if the indices exist in the array before using them
    $timezoneFormat = (isset($matches[1]) && isset($matches[2]))
        ? sprintf('%+d:%02d', $matches[1], $matches[2])
        : '+0000'; // Default to UTC if timezone is not found

    // Create a DateTime object with the timezone
    $dateTime = new DateTime($dateString, new DateTimeZone($timezoneFormat));

    return $dateTime->format("Y-m-d H:i:s");
}


/**
 * Insert news into the database.
 *
 * @param mysqli $conn The database connection.
 * @param array $news The news data.
 */
function insertNews($conn, $specificNews)
{
    $sqlInsertNews = "INSERT INTO `news`(`title`, `short_description`, `permanlink`,
         `date`, `url_image`, `news_source_id`,`user_id`, `category_id`) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsertNews);
    $stmt->bind_param(
        "sssssiii",
        $specificNews['title'],
        $specificNews['description'],
        $specificNews['link'],
        $specificNews['finalDate'],
        $specificNews['image'],
        $specificNews['newsSourceId'],
        $specificNews['userId'],
        $specificNews['categoryId']
    );

    $stmt->execute();
    return $stmt->insert_id;
}

/** Extracts and returns an array of tags from the given item.
*
* @param mixed $item The item with the news information from which to extract tags.
*
* @return array An array containing the tags extracted from the item.
*/
function getTags($item)
{
    $categories = [];
    if (isset($item->category)) {
        foreach ($item->category as $category) {
            $categories[] = (string)$category;
        }
    }

    return $categories;
}

/**
 * Inserts tags into the 'tags' table or retrieves their existing IDs.
 *
 * @param mysqli $conn - The MySQLi database connection.
 * @param array $tags - An array of tags to be inserted or retrieved.
 *
 * @return array - An array of tag IDs corresponding to the provided tags.
 */
function insertTags($conn, $tags)
{
    $tagIds = [];

    foreach ($tags as $tag) {
        // Escape the tag to prevent SQL injection
        $escapedTag = mysqli_real_escape_string($conn, $tag);

        // Check if the tag already exists in the 'tags' table
        $sqlSelect = "SELECT id FROM `tags` WHERE `name` = '$escapedTag'";
        $result = mysqli_query($conn, $sqlSelect);

        // If the tag exists, retrieve its ID; otherwise, insert the tag and get the new ID
        if ($result !== false && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $tagIds[] = $row['id'];
        } else {
            $sqlInsert = "INSERT INTO `tags`(`name`) VALUES ('$escapedTag')";
            mysqli_query($conn, $sqlInsert);
            $tagIds[] = mysqli_insert_id($conn);
        }
    }

    return $tagIds;
}

/**
 * Associates tags with a specific news article in the 'news_tags' table.
 *
 * @param mysqli $conn - The MySQLi database connection.
 * @param int $newsId - The ID of the news article.
 * @param array $tagsIds - An array of tag IDs to be associated with the news article.
 */
function insertAssociateTagsWithNews($conn, $newsId, $tagsIds)
{
    foreach ($tagsIds as $tagId) {
        $sql = "INSERT INTO `news_tags` (`news_id`, `tag_id`) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newsId, $tagId);
        $stmt->execute();
    }
}
