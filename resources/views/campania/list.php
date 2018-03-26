<div class="content">
    <div class="container">
        <h3>Campañas &raquo; Lista</h3>

        <?php if (count($result) >= 1): ?>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Valor Número</th>
                        <th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= $row->getNombre() ?></td>
                            <td>$<?= $row->getValorNumero() ?></td>
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
        <?php else: ?>
            <p>No existen registros.</p>
        <?php endif; ?>
    </div>
</div>
