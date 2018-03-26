<script>
    $(document).on('ready', function() {

        $('#nombreCompleto').putCursorAtEnd();

    });
</script>

<div class="container">
    <h3><?= ($editing? 'Editar' : 'Cargar') ?> un familiar de <?= $socioJefe->getNombreCompleto() ?></h3>
    <form method="post" action="<?= ($editing? 'familiares_update' : 'familiares_store') ?>">

		<!-- El id si lo estamos modificando -->
        <input type="hidden" name="id" value="<?= isset($familiar)? $familiar->getId() : "" ?>">

		<!-- Socio jefe de familia -->
		<input type="hidden" name="idSocioJefeFamilia" value="<?= $socioJefe->getId() ?>">

        <div class="row">
            <div class="col-lg-3">
                <label for="">Apellido y nombre</label>
                <br>
                <input type="text" name="nombreCompleto" id="nombreCompleto" class="form-control" value="<?= isset($familiar)? $familiar->getNombreCompleto() : "" ?>">
            </div>
			<div class="col-lg-3">
                <label for="">Tipo parentesco</label>
                <br>
				<select name="codTipoParentesco" class="form-control">
					<option value="">elegir...</option>
					<?php foreach(App\Models\MapTipos::TIPOS_PARENTESCO as $cod => $tipo): ?>
						<?php
							$selected = "";
							if (isset($familiar) && $cod == $familiar->getCodTipoParentesco())
								$selected = "selected";
						?>
						<option value="<?= $cod ?>" <?= $selected ?>><?= $tipo ?></option>
					<?php endforeach; ?>
				</select>
            </div>
            <div class="col-lg-3">
                <label for="">Tipo doc.</label>
                <br>
                <select name="codTipoDocUnico" class="form-control">
                    <option value="">elegir...</option>
                    <?php foreach(App\Models\MapTipos::TIPOS_DOC_UNICO as $cod => $tipo): ?>
                        <?php
                            $selected = "";
                            if (isset($familiar) && $cod == $familiar->getCodTipoDocUnico())
                                $selected = "selected";
                        ?>
                        <option value="<?= $cod ?>" <?= $selected ?>><?= $tipo ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label for="">Nro doc.</label>
                <br>
                <input type="text" name="numeroDocUnico" id="" class="form-control" value="<?= isset($familiar)? $familiar->getNumeroDocUnico() : "" ?>">
            </div>
        </div>
        <br>
        <div class="row">
			<div class="col-lg-3">
                <label for="">Fecha nac.</label>
                <br>
                <input type="text" name="fechaNacimiento" id="" class="form-control" value="<?= isset($familiar)? date('d-m-Y', strtotime($familiar->getFechaNacimiento())) : "" ?>" placeholder="DD-MM-YYYY" title="DD-MM-YYYY" pattern="^\d{2}-\d{2}-\d{4}$">
            </div>
        </div>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Aceptar">
    </form>
</div>
