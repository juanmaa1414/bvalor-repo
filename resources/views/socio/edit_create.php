<script>
    $(document).on('ready', function() {

        // Autocompletar de busqueda vendedor.
        $('#vendedor-search').typeahead({
            minLength: 3,
            highlight: true,
            // Traer de un ajax.
            source: function(query, process) {
                return $.get(BASE_URL + 'vendedores/search_ajax?terms='+query, {}, 'json')
                            .done(function(data) {
                                //console.log(data);
                                return process(data);
                            });
            },
            // Al seleccionar, recibe el marcado.
            afterSelect: function(item) {
                $('#idVendedor').val(item.id);
                $('#vendedor-search').attr('readonly', 'readonly');
                $('#vend-info').remove();
            }
        });

        // Autocompletar de busqueda socio referente.
        $('#socio-search').typeahead({
            minLength: 3,
            highlight: true,
            // Traer de un ajax.
            source: function(query, process) {
                return $.get(BASE_URL + 'socios/search_ajax?terms='+query, {}, 'json')
                            .done(function(data) {
                                return process(data);
                            });
            },
            // Al seleccionar, recibe el marcado.
            afterSelect: function(item) {
                $('#idSocioReferente').val(item.id);
                $('#socio-search').attr('readonly', 'readonly');
                $('#socio-ref-info').remove();
            }
        });

        $('#nombreCompleto').putCursorAtEnd();

    });
</script>

<div class="container">
    <h3><?= ($editing? 'Editar' : 'Cargar') ?> un socio</h3>
	TODO: validar q dni no exista
    <form method="post" action="<?= ($editing? 'update' : 'store') ?>" autocomplete="off">

        <input type="hidden" name="id" value="<?= isset($socio)? $socio->getId() : "" ?>">

        <div class="row">
            <div class="col-lg-3">
                <label for="">Apellido y nombre</label>
                <br>
                <input type="text" name="nombreCompleto" id="nombreCompleto" class="form-control" value="<?= isset($socio)? $socio->getNombreCompleto() : "" ?>" autofocus required>
            </div>
			<div class="col-lg-3">
                <label for="">Numero de socio</label>
                <br>
                <input type="text" name="" id="numeroSocio" class="form-control" value="<?= isset($socio)? $socio->getNumeroSocio() : "" ?>" disabled>
            </div>
            <div class="col-lg-3">
                <label for="">Tipo doc.</label>
                <br>
                <select name="codTipoDocUnico" class="form-control" required>
                    <option value="">elegir...</option>
                    <?php foreach(App\Models\MapTipos::TIPOS_DOC_UNICO as $cod => $tipo): ?>
                        <?php
                            $selected = "";
                            if (isset($socio) && $cod == $socio->getCodTipoDocUnico())
                                $selected = "selected";
                        ?>
                        <option value="<?= $cod ?>" <?= $selected ?>><?= $tipo ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label for="">Nro doc.</label>
                <br>
                <input type="text" name="numeroDocUnico" id="" class="form-control" value="<?= isset($socio)? $socio->getNumeroDocUnico() : "" ?>" required>
            </div>
        </div>
        <br>
        <div class="row">
			<div class="col-lg-3">
                <label for="">Domicilio</label>
                <br>
                <input type="text" name="domicilio" id="" class="form-control" value="<?= isset($socio)? $socio->getDomicilio() : "" ?>" required>
            </div>
            <div class="col-lg-3">
                <label for="">Teléfono</label>
                <br>
                <input type="text" name="telefono" id="" class="form-control" value="<?= isset($socio)? $socio->getTelefono() : "" ?>" required>
            </div>
            <div class="col-lg-3">
                <label for="">Vendedor</label>
                <br>
                <input type="text" id="vendedor-search" class="form-control" value="" placeholder="Buscar por nombre o DNI" autocomplete="off">
                <small id="vend-info" class="tag-info"><?= (isset($vend) && $vend)? $vend->getNombreCompleto() : "" ?></small>
                <input type="hidden" name="idVendedor" id="idVendedor" class="form-control" value="<?= isset($socio)? $socio->getIdVendedor() : "0" ?>">
            </div>
            <div class="col-lg-3">
                <label for="">Socio referente</label>
                <br>
                <input type="text" id="socio-search" class="form-control" value="" placeholder="Buscar por nombre o DNI" autocomplete="off">
                <small id="socio-ref-info" class="tag-info"><?= (isset($socioRef) && $socioRef)? $socioRef->getNombreCompleto() : "" ?></small>
                <input type="hidden" name="idSocioReferente" id="idSocioReferente" class="form-control" value="<?= isset($socio)? $socio->getIdSocioReferente() : "0" ?>">
            </div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-3">
                <label for="">Tipo pers.</label>
                <br>
                <select name="codTipoPersona" class="form-control">
                    <option value="">elegir...</option>
                    <?php foreach(\App\Models\MapTipos::TIPOS_PERSONA as $cod => $tipo): ?>
                        <?php
                            $selected = "";
                            if (isset($socio) && $cod == $socio->getCodTipoPersona())
                                $selected = "selected";
                        ?>
                        <option value="<?= $cod ?>" <?= $selected ?>><?= $tipo ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <br><br>
                <input type="checkbox" name="esActivo" id="esActivo" value="1" checked>
                <label for="esActivo">Es Activo</label>
            </div>
        </div>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Aceptar">
    </form>
    <br> <hr>

    <!-- Si estamos modificando/viendo un socio -->
    <?php if (isset($socio)): ?>
        <h3>Familiares del socio
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="familiares_create?id_socio=<?= $socio->getId() ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Nuevo familiar</a>
        </h3>
        <?php if (count($sociosFamiliares) >= 1): ?>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Doc.</th>
                        <th>Fecha nac.</th>
                        <th>Parentesco</th>
                        <th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sociosFamiliares as $fam): ?>
                        <tr>
                            <td><?= $fam->getNombreCompleto() ?></td>
                            <td><?= $fam->getNumeroDocUnico() ?></td>
                            <td><?= date('d-m-Y', strtotime($fam->getFechaNacimiento())) ?></td>
                            <td><?= \App\Models\MapTipos::TIPOS_PARENTESCO[$fam->getCodTipoParentesco()] ?></td>
                            <td colspan="2">
                                <a class="btn btn-primary btn-xs link-edit" href="familiares_edit?id_socio=<?= $socio->getId() ?>&id_fam=<?= $fam->getId(); ?>" title="Editar">
                                    Editar
                                </a>
                                <a class="btn btn-warning btn-xs btn-delete link-del" href="familiares_delete?id=<?= $fam->getId(); ?>" title="Eliminar">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
