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
							<div class="alert alert-success">
								<h4><?= $message; ?></h4>
							</div>

                            <div align="center">
                                <a class="btn btn-primary" href="<?= $btn_href; ?>">Aceptar</a>
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
							<div class="alert alert-danger">
								<h4><?= $message; ?></h4>
								<p><?= $details; ?></p>
							</div>

                            <div align="center">
                                <a class="btn btn-primary" href="<?= $btn_href; ?>">Aceptar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
