<div class="container mt-5">
    <div class="mb-1">
        <h1 class="font-weight-light text-muted display-6">Categories</h1>
    </div>
    <div class="row">
        <div class="col-lg-7 mt-3">
            <div class="table-responsive">
                <!-- Table displaying categories with edit and delete actions -->
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Generate table rows dynamically from the $categories array -->
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <th class="fw-normal"><?php echo $category['name'] ?></th>
                                <th>
                                    <div class="d-flex justify-content-around">
                                        <!-- Link to edit or delete the category, using the category's ID -->
                                        <a class="btn btn-secondary btn-sm" href="<?php echo site_url(['admin', 'edit', $category['id']]); ?>">Edit</a>
                                        <a class="btn btn-danger btn-sm deleteLink" href="<?php echo site_url(['admin', 'delete', $category['id']]); ?>">Delete</a>
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
        <a class="btn btn-secondary px-5" href="<?php echo site_url('admin/create') ?>" role="button">Add new</a>
    </div>
</div>
<script src="/js/confirmDelete.js"></script>
<script src="/js/alerts.js"></script>