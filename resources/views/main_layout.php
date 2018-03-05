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
        <link href="../../vendor/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="../../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="../../css/styles.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- TODO: Adoptar estrategia que permita situar los js al final del html
        lo cual es la practica recomendada.-->
        <script src="../../vendor/js/jquery.min.js"></script>
        <script src="../../vendor/js/bootstrap.min.js"></script>
        <script src="../../vendor/js/bootstrap3-typeahead.min.js"></script>
        
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../js/ie10-viewport-bug-workaround.js"></script>
        
        <script src="../../js/jquery.putcursor.js"></script>
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
                    <a class="navbar-brand" href="#">Sis. Bomberos</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Inicio</a></li>
                        <!-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Foo</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Foo</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </div> <!-- /.nav-collapse -->
            </div>
        </nav>
        <br><br><br>
        <?= $view ?>
    </body>
</html>
