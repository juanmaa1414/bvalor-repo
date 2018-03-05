<script>
    $(document).on('ready', function() {
        
        $('#nombreCompleto').putCursorAtEnd();
        
    });
</script>

<div class="container">
    <h3><?= ($editing? 'Editar' : 'Cargar') ?> un vendedor</h3>
    <form method="post" action="<?= ($editing? 'update' : 'store') ?>">
        
        <input type="hidden" name="id" value="<?= isset($vend)? $vend->getId() : "" ?>">
        
        <div class="row">
            <div class="col-lg-3">
                <label for="">Apellido y nombre</label>
                <br>
                <input type="text" name="nombreCompleto" id="nombreCompleto" class="form-control" value="<?= isset($vend)? $vend->getNombreCompleto() : "" ?>">
            </div>
            <div class="col-lg-3">
                <label for="">Tipo doc.</label>
                <br>
                <select name="codTipoDocUnico" class="form-control">
                    <option value="">elegir...</option>
                    <?php foreach(App\Models\MapTipos::TIPOS_DOC_UNICO as $cod => $tipo): ?>
                        <?php
                            $selected = "";
                            if (isset($vend) && $cod == $vend->getCodTipoDocUnico())
                                $selected = "selected";
                        ?>
                        <option value="<?= $cod ?>" <?= $selected ?>><?= $tipo ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label for="">Nro doc.</label>
                <br>
                <input type="text" name="numeroDocUnico" id="" class="form-control" value="<?= isset($vend)? $vend->getNumeroDocUnico() : "" ?>">
            </div>
            <div class="col-lg-3">
                <label for="">Domicilio</label>
                <br>
                <input type="text" name="domicilio" id="" class="form-control" value="<?= isset($vend)? $vend->getDomicilio() : "" ?>">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-3">
                <label for="">Teléfono</label>
                <br>
                <input type="text" name="telefono" id="" class="form-control" value="<?= isset($vend)? $vend->getTelefono() : "" ?>">
            </div>
        </div>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Aceptar">
    </form>
</div>