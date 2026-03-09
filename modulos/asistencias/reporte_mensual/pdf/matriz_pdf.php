<?php
    // archivo : /modulos/asistencias/reporte_mensual/pdf/matriz_pdf.php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";
	$conn = getConnection();

	require_once __DIR__ . '/tcpdf/tcpdf.php';

	$mes  = isset($_GET['mes']) ? intval($_GET['mes']) : 0;
	$anio = isset($_GET['anio']) ? intval($_GET['anio']) : 0;

	$meses = [
    	1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',
		7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE'
		];

	$nombreMes = $meses[$mes];

	// Funciones auxiliares para la matriz
	function diaSemana($anio,$mes,$dia){
    	$dias=['D','L','M','M','J','V','S'];
		return $dias[date('w', mktime(0,0,0,$mes,$dia,$anio))];
		}

	function semanaISO($anio,$mes,$dia){
    	return date('W', mktime(0,0,0,$mes,$dia,$anio));
		}

// Colores por código
$colores = [
    'A'  => '#C6EFCE',
    'T'  => '#FFEB9C',
    'FJ' => '#FFC7CE',
    'FI' => '#FF9999',
    'VA' => '#F8CBAD',
    'DM' => '#BDD7EE',
    'PN' => '#DDEBF7',
    'PS' => '#E4DFEC',
    'FR' => '#E2E2E2',
    'NL' => '#E4DFEC',
    'F'  => '#F4B084'
];

// Estilo de celda
function estiloCelda($codigo, $esDomingo, $colores) {

	// Celda vacía
	if ($codigo === '' || $codigo === null) {
		if ($esDomingo) {
			return 'background-color:#FDE2E2; border:0.5px solid #FF6666; text-align:center; font-size:6px; line-height:6px; vertical-align:middle;';
		}
		return 'text-align:center; font-size:6px; line-height:6px; vertical-align:middle;';
	}

    // Color del código
    $bg = isset($colores[$codigo]) ? $colores[$codigo] : 'transparent';

    // Domingo con código
    if ($esDomingo) {
        return "
            background-color:#FDE2E2;
            border:0.5px solid #FF6666;
            outline: 2px solid $bg;
            font-weight:bold;
            text-align:center;
            font-size:6px;
            line-height:6px;
            vertical-align:middle;
        ";
    }

    // Día normal
    return "background-color:$bg; font-weight:bold; text-align:center; font-size:6px; line-height:6px; vertical-align:middle;";
}



	// Obtener datos de la base de datos
	$sql = "SELECT 
			ac.conductor_id,
        	ac.fecha,
			ac.tipo_codigo,
		CONCAT(LEFT(c.nombres,1),'. ',c.apellidos) AS conductor
			FROM asistencia_conductores ac
        	INNER JOIN conductores c ON c.id=ac.conductor_id
        	WHERE MONTH(ac.fecha)=$mes AND YEAR(ac.fecha)=$anio
        	ORDER BY conductor ASC, ac.fecha ASC";

	$res = $conn->query($sql);
	if(!$res) die("ERROR SQL: ".$conn->error);

	$grupos=[];
	while($r=$res->fetch_assoc()){
    	if(strlen($r['fecha'])<10) continue;

    	$cid=$r['conductor_id'];
    	$dia=intval(substr($r['fecha'],8,2));

    	if(!isset($grupos[$cid])){
			$grupos[$cid]=['nombre'=>$r['conductor'],'dias'=>[]];
    	}

		$grupos[$cid]['dias'][$dia]=$r['tipo_codigo'];
	}

$diasMes = cal_days_in_month(CAL_GREGORIAN,$mes,$anio);


// SECCIÓN 4 — Configuración del PDF
$pdf = new TCPDF('L','mm','A4');
$pdf->SetMargins(1,10,10); // MARGEN IZQUIERDO AL MÍNIMO
$pdf->AddPage();
$pdf->SetFont('helvetica','',6); // tamaño general

$html = '<h2 style="text-align:center; font-size:14px;">Matriz de Asistencias - '.$nombreMes.' - '.$anio.'</h2>';
$html .= '<table border="1" cellpadding="0.2" cellspacing="0">';

/* ============================
   FILA 1: SEMANA ISO
============================ */
$html .= '<thead><tr>';
$html .= '<th style="text-align:center; width:40px; font-size:6px;">Semana</th>';

for($d=1;$d<=$diasMes;$d++){
    $sem = semanaISO($anio,$mes,$d);
    $html .= '<th style="text-align:center; font-size:6px; line-height:6px;">'.$sem.'</th>';
}

$html .= '</tr>';

/* ============================
   FILA 2: DÍA + NÚMERO
============================ */
$html .= '<tr>';
$html .= '<th style="text-align:center; width:40px;">Conductor</th>';

for($d=1;$d<=$diasMes;$d++){

    $diaLetra = diaSemana($anio,$mes,$d);
    $bg = ($diaLetra=='D') ? 'background-color:#FFCCCC;' : '';

	// $html .= '<th style="text-align:center; font-size:6px; line-height:6px; background-color:#F2F2F2; font-weight:bold; border-bottom:1px solid #000; '.$bg.'">'.$diaLetra.'<br>'.$d.'</th>';


    $html .= '<th style="text-align:center; font-size:6px; line-height:6px; '.$bg.'">'.$diaLetra.'<br>'.$d.'</th>';
}

$html .= '</tr></thead><tbody>';

/* ============================
   FILAS DE CONDUCTORES
============================ */
foreach($grupos as $g){

    $html .= '<tr>';
    $html .= '<td style="width:40px;">'.$g['nombre'].'</td>';

    for($d=1;$d<=$diasMes;$d++){

      
		$diaLetra = diaSemana($anio,$mes,$d);
$esDomingo = ($diaLetra == 'D');

$valor = isset($g['dias'][$d]) ? $g['dias'][$d] : '';

$estilo = estiloCelda($valor, $esDomingo, $colores);

$html .= '<td style="'.$estilo.'">'.$valor.'</td>';

	
		}

    $html .= '</tr>';
}

$html .= '</tbody></table>';


// Agregar leyenda de códigos al final del PDF

$html .= '
<br><br>
<table cellpadding="2" cellspacing="0" style="font-size:8px;">

<tr>
    <td><span style="background-color:#C6EFCE; padding:1px 4px; font-weight:bold;">A</span> Asistencia</td>
    <td><span style="background-color:#FFEB9C; padding:1px 4px; font-weight:bold;">T</span> Tardanza</td>
    <td><span style="background-color:#FFC7CE; padding:1px 4px; font-weight:bold;">FJ</span> Falta Justificada</td>
    <td><span style="background-color:#FF9999; padding:1px 4px; font-weight:bold;">FI</span> Falta Injustificada</td>
</tr>

<tr>
    <td><span style="background-color:#F8CBAD; padding:1px 4px; font-weight:bold;">VA</span> Vacaciones</td>
    <td><span style="background-color:#BDD7EE; padding:1px 4px; font-weight:bold;">DM</span> Descanso Médico</td>
    <td><span style="background-color:#DDEBF7; padding:1px 4px; font-weight:bold;">PN</span> Permiso con Goce</td>
    <td><span style="background-color:#E4DFEC; padding:1px 4px; font-weight:bold;">PS</span> Permiso sin Goce</td>
</tr>

<tr>
    <td><span style="background-color:#E2E2E2; padding:1px 4px; font-weight:bold;">FR</span> Franca</td>
    <td><span style="background-color:#E4DFEC; padding:1px 4px; font-weight:bold;">NL</span> No Laborable</td>
    <td><span style="background-color:#F4B084; padding:1px 4px; font-weight:bold;">F</span> Feriado</td>
</tr>

</table>
';


$pdf->writeHTML($html,true,false,true,false,'');
$pdf->Output("matriz_$mes-$anio.pdf",'I');
