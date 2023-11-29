<section>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-7">
                <h1 class="font-weight-light text-muted display-6 mt-5"><?php echo $actionTitle ?></h1>
                <form action="<?= site_url('users/newsSources/save'); ?>" method="POST" class="form-inline mt-3" role="form">
                    <input type="hidden" class="form-control" name="id" value="<?php echo isset($newsSource) ? $newsSource['id'] : '' ?>">
                    <div class="border-bottom border-top">
                        <div class="row mb-5 mt-4">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="<?php echo isset($newsSource) ? $newsSource['name'] : '' ?>" id="nameSource" placeholder="Name" required="true">
                            </div>
                            <div class="form-group mt-4">
                                <input type="text" class="form-control" name="url" value="<?php echo isset($newsSource) ? $newsSource['url'] : '' ?>" id="rss" placeholder="RSS URL" required="true">
                            </div>
                            <div class="col-md-6 mt-4">
                                <select id="category" class="form-control" name="category" required="true">
                                    <!-- Display options for selecting a category, retrieved from the database -->
                                    <?php
                                    foreach ($categories as $category) {
                                        $selected = '';
                                        if (isset($newsSource))
                                            $selected = ($newsSource['category_id'] == $category['id']) ? 'selected="true"' : '';

                                        echo "<option value=\"" . $category['id'] . "\" $selected >" . $category['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 pt-2 col-3">
                        <input type="submit" class="btn btn-secondary" value="Add New">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="/js/validateNewsSources.js"></script>