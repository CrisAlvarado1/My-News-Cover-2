<section class="jumbotron text-center">
    <div class="container">
        <h1 class="font-weight-light text-muted display-6 mt-5">
            <?= ($isPublic) ? 'Your Cover is Public' : 'Make Your Cover Public' ?>
        </h1>
        <hr class="w-25 mx-auto">
    </div>
</section>

<section>
    <div class="container mt-5 text-center">
        <!-- Show the link of the public cover -->
        <?php if (isset($accessLink)) : ?>
            <div class="alert alert-success" role="alert">
                <span>Cover published successfully! Share the link:</span>
                <a href="<?= $accessLink ?>" class="alert-link" target="_blank"><?= $accessLink ?></a>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('users/news/public') ?>" method="post">
            <!-- User confirmation for make public -->
            <?php if (!$isPublic) : ?>
                <div class="form-group mt-4">
                    <label>
                        <input type="checkbox" name="confirm" required>
                        I confirm my decision to make my cover public.
                    </label>
                </div>
            <?php endif; ?>
            <!-- User confirmation for make private -->
            <?php if ($isPublic) : ?>
                <div class="form-group mt-4">
                    <label>
                        <input type="checkbox" name="disconfirm" required>
                        I confirm my decision to make my cover private.
                    </label>
                </div>
            <?php endif; ?>

            <div class="form-group mt-5">
                <button type="submit" class="btn btn-secondary">
                    <?= ($isPublic) ? 'Make Private' : 'Make Public' ?>
                </button>
            </div>
        </form>
    </div>
</section>