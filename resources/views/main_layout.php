<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Sistema administracion de socios y campañas para cuartel bomberos">
        <meta name="author" content="Juan M. Fernandez, Maira Gonzalez">
        <!--<link rel="icon" href="../../favicon.ico">-->

        <title><?= $title ?></title>

        <!-- Bootstrap core CSS -->
        <link href="<?= url('/') ?>/public/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?= url('/') ?>/public/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="<?= url('/') ?>/public/css/styles.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- TODO: Adoptar estrategia que permita situar los js al final del html
        lo cual es la practica recomendada.-->
        <script src="<?= url('/') ?>/public/vendor/js/jquery.min.js"></script>
        <script src="<?= url('/') ?>/public/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script src="<?= url('/') ?>/public/vendor/js/bootstrap3-typeahead.min.js"></script>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= url('/') ?>/public/js/ie10-viewport-bug-workaround.js"></script>

        <!-- Libreria de UI para situar cursor al final de los input -->
        <script src="<?= url('/') ?>/public/js/jquery.putcursor.js"></script>

        <!-- API y funcionalidad global de nuestro proyecto -->
        <script src="<?= url('/') ?>/public/js/general_api.js"></script>
		<script>
			/*
			setTimeout(function() {
				$('img[alt="www.000webhost.com"]').remove();
			}, 400);
			*/
		</script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Navegación</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= route('inicio/index') ?>">Sis. Bomberos</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li><a href="<?= route('inicio/index') ?>">Inicio</a></li>
						<li><a href="<?= route('socios/listed') ?>">Socios</a></li>
						<li><a href="<?= route('vendedores/listed') ?>">Vendedores</a></li>
						<li><a href="<?= route('campanias/listed') ?>">Campañas</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pagos <span class="caret"></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= route('planes_abono/listed') ?>">Planes de abono</a></li>
								<li><a href="<?= route('planes_abono/crear_plan') ?>">Carga de plan socio</a></li>
								<li><a href="<?= route('planes_abono/crear_plan_manual') ?>">Carga de plan manual</a></li>
								<li><a href="<?= route('planes_abono/crear_transf_num') ?>">Transferir número</a></li>
                            </ul>
                        </li>
                    </ul>
                </div> <!-- /.nav-collapse -->
            </div>
        </nav>
        <br><br><br>
        <?= $view ?>

		<div class="modal fade" id="delete-dialog" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4>Borrar elemento</h4>
						<em>¿Está seguro?</em>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <a class="btn btn-danger" id="btn-eliminar-action" href="#">Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
