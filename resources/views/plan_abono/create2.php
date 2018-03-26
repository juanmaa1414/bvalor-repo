<script>
    $(document).on('ready', function() {

		// Autocompletar de busqueda socio.
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
                $('#idSocio').val(item.id);
                $('#socio-search').attr('readonly', 'readonly');
            }
        });

        $('#socio-search').focus();

    });
</script>

<div class="container">
    <h3>Cargar un plan manual</h3>
    <form method="post" action="grabar_plan_manual">

        <!--<input type="hidden" name="id" value="">-->

        <div class="row">
            <div class="col-lg-3">
                <label for="">Socio</label>
                <br>
                <input type="text" id="socio-search" class="form-control" value="" placeholder="Buscar por nombre o DNI" autocomplete="off">
                <input type="hidden" name="idSocio" id="idSocio" class="form-control" value="">
            </div>
			<div class="col-lg-3">
                <label for="">Costo del número</label>
                <br>
				<div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" name="valorNumero" id="" class="form-control" value="">
                </div>
            </div>
			<div class="col-lg-3">
                <label for="">Meses a abonar DESDE</label>
                <br>
                <select name="mesDesde" class="form-control">
                	<option value="1">Enero</option>
					<option value="2">Febrero</option>
					<option value="3">Marzo</option>
					<option value="4">Abril</option>
					<option value="5">Mayo</option>
					<option value="6">Junio</option>
					<option value="7">Julio</option>
					<option value="8">Agosto</option>
					<option value="9">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
                </select>
            </div>
			<div class="col-lg-3">
                <label for="">HASTA</label>
                <br>
                <select name="mesHasta" class="form-control">
					<option value="1">Enero</option>
					<option value="2">Febrero</option>
					<option value="3">Marzo</option>
					<option value="4">Abril</option>
					<option value="5">Mayo</option>
					<option value="6">Junio</option>
					<option value="7">Julio</option>
					<option value="8">Agosto</option>
					<option value="9">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12" selected>Diciembre</option>
                </select>
            </div>
        </div>
        <br>
		<label>Números</label>
        <div class="row">
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
			<div class="col-lg-1">
                <input type="text" name="numeros[]" id="" class="form-control" value="">
            </div>
        </div>
        <br><br>
        <input type="submit" class="btn btn-primary" value="Aceptar">
    </form>
</div>
