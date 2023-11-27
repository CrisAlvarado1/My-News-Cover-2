<section class="content mt-5">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-7">
                <h1 class="mb-1 font-weight-normal text-muted display-6">User Login</h1>
                <?= session()->getFlashdata('error') ?>
                <?= validation_list_errors() ?>
                <form action="<?= site_url('user/authenticate') ?>" method="post" class="form-inline" role="form">
                    <?= csrf_field() ?>
                    <div class="form-group mb-5">
                        <hr class="hr-light">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email Address" required="true">
                    </div>
                    <div class="form-group mt-4">
                        <input type="password" class="form-control" name="password" placeholder="Password" required="true">
                    </div>
                    <div class="form-group mt-5">
                        <hr class="hr-light">
                    </div>
                    <div class="form-group mt-4 pt-3 col-3">
                        <input type="submit" class="btn btn-secondary" value="Login">
                    </div>
                </form>
                <div class="mt-3">
                    <p>If you don't have an account, <a href="<?php echo site_url('users/index') ?>" class="link-primary">Sign up here!</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="js/alerts.js"></script>