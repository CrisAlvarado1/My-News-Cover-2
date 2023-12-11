<section class="jumbotron text-center">
    <div class="container">
        <h1 class="jumbotron-heading text-muted mt-4 display-6">
            <?php echo $largeTitle ?>
        </h1>
        <hr class="w-25 mx-auto">
        <!-- Show the option to make public cover -->
        <?php if (!isset($userCover)) : ?>
            <a href="<?php echo site_url('users/news/public') ?>" class="btn btn-link btn-sm">Make Public Cover</a>
        <?php endif; ?>
    </div>
</section>
<!-- Filter area: -->
<section>
    <div class="container mt-4">
        <div class="row text-center justify-content-center">
            <!-- The "portada" filter will always be there, it is where all the news is shown -->
            <div class="col-md-2 border <?php echo (isset($categoryId)) ? '' : 'selected';  ?>">
                <a href="<?php echo site_url($routeCategory) ?>" class="btn btn-block w-100 h-100 text-decoration-none">Portada</a>
            </div>
            <!-- Generates filters based on user's related news categories -->
            <?php foreach ($filters as $filters) : ?>
                <?php
                $selected = '';
                if (isset($categoryId))
                    $selected = ($filters['category_id'] === $categoryId) ? 'selected' : '';
                ?>
                <div class="col-md-2 border <?php echo $selected ?>">
                    <a href="<?php echo site_url($routeCategory . '/' . $filters['category_id']) ?>" class="btn btn-block w-100 h-100 text-decoration-none"><?php echo $filters['category_name']; ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Filter area for tags: -->
<?php if (!empty($tags)) : ?>
    <section class="mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0">
                        <div class="card-body">
                            <h3 class="card-title text-center h5">Filter by Tags</h3>
                            <?php $category = (isset($categoryId)) ? "/" . $categoryId : '' ?>

                            <form id="filterForm" action="<?= site_url('users/news/index/tags' . $category) ?>" method="post">
                                <div class="row border">
                                    <!-- Generates filters based on the news's related tags -->
                                    <?php foreach ($tags as $tag) : ?>
                                        <div class="col-md-2 mb-1">
                                            <div class="form-check">
                                                <?php
                                                // Determine whether the current tag is selected based on the tagsSelected array
                                                $checked = '';
                                                if (isset($tagsSelected))
                                                    $checked = (in_array($tag['tag_id'], $tagsSelected)) ? 'checked="checked"' : '';
                                                ?>
                                                <!-- Checkbox input for the tag with dynamic ID and value -->
                                                <input class="form-check-input" type="checkbox" name="tagsNews[]" value="<?= $tag['tag_id'] ?>" id="tag<?= $tag['tag_id'] ?>" <?= $checked ?>>
                                                <!-- Label for the checkbox displaying the tag name -->
                                                <label class="form-check-label" for="tag<?= $tag['tag_id'] ?>">
                                                    <?= $tag['name_tag']; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<!-- Search area for news: -->
<?php if (!isset($userCover)) : ?>
    <section>
        <div class="container mt-4">
            <div class="row">
                <!-- Search form -->
                <div class="col-md-4 mt-3">
                    <?php $category = (isset($categoryId)) ? "/" . $categoryId : '' ?>
                    <form action="<?php echo site_url('users/news/index/search' . $category) ?>" method="post">
                        <div class="input-group">
                            <!-- Input field for entering search keywords -->
                            <input type="text" name="keywords" class="form-control" placeholder="Search news..." required="true" value="<?php echo (isset($dataKeywords)) ? $dataKeywords : '' ?>">
                            <!-- Submit button for initiating the search -->
                            <input type="submit" class="btn btn-secondary" value="Buscar">
                        </div>
                    </form>
                </div>
                <!-- Clear filters button -->
                <div class="col-md-8 text-end mt-3">
                    <a href="<?php echo site_url('users/news/index' . $category) ?>" class="btn btn-secondary" id="limpiarFiltros">Clean Filters</a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<section class="album py-5">
    <div class="container">
        <div class="row">
            <!-- Validates if there are any news in the user's timeline  -->
            <?php if (!empty($allNews)) { ?>
                <!-- If there are news, generates the cards to display them (based in a filter) -->
                <?php foreach ($allNews as $news) :  ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow">
                            <div class="position-relative news-image">
                                <a href="<?php echo $news['permanlink']; ?>" target="_blank">
                                    <!-- Display the image if available, otherwise show a default image -->
                                    <img class="card-img-top mt-1 news-image img-fluid" alt="News Reference Image" src="<?php echo !empty($news['url_image']) ? $news['url_image'] : '/images/general/default.jpg'; ?>">
                                </a>
                            </div>
                            <div class="card-body">
                                <!-- Display the date if available, otherwise show a message -->
                                <small><?php echo $news['date'] ? date("d/m/Y g:ia", strtotime($news['date'])) : 'No date available'; ?></small>
                                <a href="<?php echo $news['permanlink']; ?>" target="_blank" class="text-decoration-none link-dark">
                                    <h2 class="card-title h5"><?php echo $news['title']; ?></h2>
                                </a>
                                <small class="card-text"><?php echo $news['name_source']; ?> -</small>
                                <small class="card-text"><?php echo $news['category_name']; ?></small>
                                <p class="card-text"><?php echo $news['short_description']; ?></p>
                                <div class="position-absolute bottom-0 mb-2">
                                    <a href="<?php echo $news['permanlink']; ?>" target="_blank">Ver noticia</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php } else { ?>
                <!-- Display a message when no news is available -->
                <div class="col-12 text-center">
                    <p class="text-muted mt-4 h5">No news available at the moment.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>