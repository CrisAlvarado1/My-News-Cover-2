<section>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-7">
                <h1 class="font-weight-light text-muted display-6 mt-5"><?php echo $actionTitle ?></h1>
                <?= session()->getFlashdata('error') ?>
                <?= validation_list_errors() ?>
                <form action="<?= site_url('admin/save'); ?>" method="POST" class="form-inline mt-3" role="form">
                    <input type="hidden" class="form-control" name="id" value="<?php echo isset($category) ? $category['id'] : '' ?>">
                    <div class="border-bottom border-top">
                        <div class="row mb-5 mt-4">
                            <div class="col-md-6 mb-5">
                                <input type="text" class="form-control" name="name" value="<?php echo isset($category) ? $category['name'] : '' ?>" id="categoryName" placeholder="Name" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 pt-2 col-3">
                        <input type="submit" class="btn btn-secondary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>