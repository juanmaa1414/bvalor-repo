<?php
if ( ! isset($btn_href)) {
    $btn_href = route('socios/listed'); // TODO: poner un home
}
?>

<div class="content">
    <div class="container">
        <?php if ($success): ?>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="thumbnail">
                        <div class="caption">
                            <h3>Información</h3>

                            <p class="alert alert-success">
                                <?= $message; ?>
                            </p>

                            <div align="center">
                                <a class="btn btn-success" href="<?= $btn_href; ?>">Continuar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="thumbnail">
                        <div class="caption">
                            <h3>Información</h3>

                            <p class="alert alert-danger">
                                <?= $message; ?>
                            </p>

                            <div align="center">
                                <a class="btn btn-success" href="<?= $btn_href; ?>">Continuar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
