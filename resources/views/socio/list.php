<div class="content">
    <div class="container">
        <h3>Socios &raquo; Lista</h3>
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="" method="get">
					<div class="row">
						<div class="col-lg-2">
							<input type="text" class="form-control" name="nombre_dni" placeholder="Nombre / N&deg; doc"
							 		value="<?= app('request')->input('nombre_dni') ?: '' ?>" autocomplete="off">
						</div>
						<div class="col-lg-2">
							<input type="text" class="form-control" name="numeroSocio" placeholder="N&deg; socio"
							 		value="<?= app('request')->input('numeroSocio') ?: '' ?>" autocomplete="off">
						</div>
						<div class="col-lg-2">
							<input type="submit" class="btn btn-primary" value="Buscar">
						</div>
					</div>


				</form>
			</div>
		</div>
        <?php if (count($result) >= 1): ?>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Doc.</th>
                        <th>Domicilio</th>
                        <th>Tel.</th>
                        <th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= $row->getNombreCompleto() ?></td>
                            <td><?= $row->getNumeroDocUnico() ?></td>
                            <td><?= $row->getDomicilio() ?></td>
                            <td><?= $row->getTelefono() ?></td>
                            <td colspan="2">
                                <a class="btn btn-primary btn-xs link-edit" href="edit?id=<?= $row->getId(); ?>" title="Editar">
                                    Editar
                                </a>
                                <a class="btn btn-warning btn-xs btn-delete link-del" href="delete?id=<?= $row->getId(); ?>" title="Eliminar">
                                    Eliminar...
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
			<?= $pagelinks ?>
        <?php endif; ?>
    </div>
</div>
