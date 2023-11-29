<section>
    <div class="container mt-5">
        <div class="mb-1">
            <h1 class="font-weight-light text-muted display-6">News Sources</h1>
        </div>
        <div class="row">
            <div class="col-lg-7 mt-3">
                <div class="table-responsive">
                    <!-- Table displaying news sources with edit and delete actions -->
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Generate table rows dynamically from the $newsSource array -->
                            <?php foreach ($newsSources as $newsSource) : ?>
                                <tr>
                                    <th class="fw-normal"><?php echo $newsSource['name'] ?></th>
                                    <th class="fw-normal"><?php echo $newsSource['category_name'] ?></th>
                                    <th>
                                        <div class="d-flex justify-content-around">
                                            <!-- Link to edit or delete the news source, using the news source ID -->
                                            <a class="btn btn-secondary btn-sm" href="<?php echo site_url(['users', 'newsSources', 'edit', $newsSource['id']]); ?>">Edit</a>
                                            <a class="btn btn-danger btn-sm deleteLink" href="<?php echo site_url(['users', 'newsSources', 'delete', $newsSource['id']]); ?>">Delete</a>
                                        </div>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3 mb-4">
            <a class="btn btn-secondary px-5" href="<?php echo site_url('users/newsSources/create') ?>" role="button">Add new</a>
        </div>
    </div>
</section>
<script src="/js/confirmDelete.js"></script>