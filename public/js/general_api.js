function set_env_variables() {
    const PROTOCOL = location.protocol;
    const PORT = location.port;
    const HOSTNAME = (!PORT) ? location.hostname : location.hostname + ':' + PORT;
    const PATHNAME = location.pathname;

    var indexStart = PATHNAME.indexOf('/', 0),
        indexEnd = PATHNAME.indexOf('/', 1);
    const SYSTEM_CODE_NAME = PATHNAME.substring(indexStart + 1, indexEnd);

    window.BASE_URL = PROTOCOL + '//' + HOSTNAME + '/';
}

$(document).on('ready', function() {
    set_env_variables();

	// Usuario intenta borrar un registro
	// Se le presenta el modal de confirmacion.
	$('.link-del').on('click', function(e) {
		e.preventDefault();

		$('#btn-eliminar-action').attr('href', $(this).attr('href'));
		$('#delete-dialog').modal('show');
	});
});
