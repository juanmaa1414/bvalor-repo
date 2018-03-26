<div class="content">
    <div class="container">
        <h3>Planes de abono &raquo; Lista</h3>

        <?php if (count($result) >= 1): ?>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Socio</th>
                        <th>Campaña</th>
						<th>Fecha</th>
						<th>Meses</th>
						<th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $plan): ?>
                        <tr>
                            <td><?= $plan->getSocio()->getNombreCompleto() ?></td>
							<td><?= $plan->getCampania()->getNombre() ?></td>
							<td><?= date('d-m-Y', $plan->getTimestampAlta()) ?></td>
							<td><?= $plan->getMesDesde() . ' al ' . $plan->getMesHasta() ?></td>
                            <td colspan="2">
                                <a class="btn btn-primary btn-xs" href="show?id=<?= $plan->getId(); ?>" title="Detalle">
                                    Detalle
                                </a>
								<a class="btn btn-primary btn-xs" href="report?id=<?= $plan->getId(); ?>" title="Cartón">
                                    Ver cartón
                                </a>
                                <a class="btn btn-warning btn-xs btn-delete link-del" href="delete?id=<?= $plan->getId(); ?>" title="Eliminar">
                                    Baja...
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No existen registros.</p>
        <?php endif; ?>
    </div>
</div>
