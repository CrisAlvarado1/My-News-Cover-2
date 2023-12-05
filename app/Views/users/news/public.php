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
        <?php if (isset($accessLink)) : ?>
            <div class="alert alert-success" role="alert">
                <span>¡Portada publicada con éxito! Comparte el enlace:</span>
                <a href="<?= $accessLink ?>" class="alert-link"><?= $accessLink ?></a>
            </div>
        <?php endif; ?>
        <form action="<?= site_url('users/news/public') ?>" method="post">
            <!-- Confirmación del usuario -->
            <?php if (!$isPublic) : ?>
                <div class="form-group mt-4">
                    <label>
                        <input type="checkbox" name="confirm" required>
                        Confirmo mi decisión de hacer pública mi portada.
                    </label>
                </div>
            <?php endif; ?>
            <?php if ($isPublic) : ?>
                <div class="form-group mt-4">
                    <label>
                        <input type="checkbox" name="disconfirm" required>
                        Confirmo mi decisión de hacer privada mi portada.
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