<?php
namespace App\Models;

/**
 * Description of tiposdoc
 *
 * @author Juan M. Fernandez
 */
class MapTipos {
	const TIPOS_DOC_UNICO = [
        '1' => 'DNI',
        '2' => 'CUIT',
        '3' => 'LIB. CIVICA',
        '4' => 'LIB. ENROLAMIENTO'
    ];

    const TIPOS_PERSONA = [
        '1' => 'PERS. FISICA',
        '2' => 'PERS. JURIDICA'
    ];

    const TIPOS_PARENTESCO = [
        '1' => 'PADRE',
        '2' => 'MADRE',
        '3' => 'HIJO/A',
        '4' => 'HIJASTRO/A',
        '5' => 'TUTELADO',
        '6' => 'PAREJA',
        '7' => 'ABUELO/A',
        '8' => 'BISABUELO/A',
        '9' => 'TIO/A',
        '10' => 'SOBRINO/A',
        '11' => 'NIETO/A',
		'12' => 'BISNIETO/A'
    ];
}
