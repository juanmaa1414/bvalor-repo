<script>
    $(document).on('ready', function() {
        
        $('#nombre').putCursorAtEnd();
        
    });
</script>

<div class="container">
    <h3><?= ($editing? 'Editar' : 'Cargar') ?> una campañia</h3>
    <form method="post" action="<?= ($editing? 'update' : 'store') ?>">
        
        <input type="hidden" name="id" value="<?= isset($camp)? $camp->getId() : "" ?>">
        
        <div class="row">
            <div class="col-lg-3">
                <label for="">Nombre</label>
                <br>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= isset($camp)? $camp->getNombre() : "" ?>">
            </div>
            <div class="col-lg-3">
                <label for="">Costo del número</label>
                <br>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" name="valorNumero" id="" class="form-control" value="<?= isset($camp)? $camp->getValorNumero() : "" ?>">
                </div>
            
        </div>
        <br><br> <br><br><br>
        <input type="submit" class="btn btn-primary" value="Aceptar">
    </form>
</div>