<?php
session_start();

$ruta_raiz = "../..";

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

/**
  * Pagina Menu_Masiva.php que muestra el contenido de las Carpetas
  * Creado en la SSPD en el año 2003
  * Se anadio compatibilidad con variables globales en Off
  * @autor Jairo Losada 2009-05
  * @licencia GNU/GPL V 3
  */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tip3Nombre  = $_SESSION["tip3Nombre"];
$tip3desc    = $_SESSION["tip3desc"];
$tip3img     = $_SESSION["tip3img"];


include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once("$ruta_raiz/include/combos.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

/**
 * Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
 *
 * @param char $var
 * @return numeric
 */

function return_bytes($val)
{	$val = trim($val);
	$ultimo = strtolower($val{strlen($val)-1});
	switch($ultimo)
	{	// El modificador 'G' se encuentra disponible desde PHP 5.1.0
		case 'g':	$val *= 1024;
		case 'm':	$val *= 1024;
		case 'k':	$val *= 1024;
	}
	return $val;
}
?>
<html>
<head>
<link rel="stylesheet" href="<?=$ruta_raiz.$ESTILOS_PATH?>orfeo.css">
</head>
<body bgcolor="#FFFFFF" topmargin="0">
<script language="JavaScript" type="text/JavaScript">

/**
* Valida que el formulario desplegado se encuentre adecuadamente diligenciado
*/

function validar() {

	//myString.indexOf("a")
	archDocto = document.formAdjuntarArchivos.archivoPlantilla.value;
	//Adición Johnny: debe aceptar archivos .odt.
	if (
	        (archDocto.substring(archDocto.length-1-3,archDocto.length)).indexOf(".doc") == -1
	     &&
	     	(archDocto.substring(archDocto.length-1-3,archDocto.length)).indexOf(".odt") == -1
	      ){
		alert ("El archivo de plantilla debe ser .doc o .odt: " +archDocto.substring(archDocto.length-1-3,archDocto.length));
		return false;
	}else
	{
	}
	archCSV = document.formAdjuntarArchivos.archivoCsv.value;

	if ( (archCSV.substring(archCSV.length-1-3,archCSV.length)).indexOf(".csv") == -1 ){
		alert ( "El archivo de datos debe ser .csv" );
		return false;
	}


	if (document.formAdjuntarArchivos.archivoPlantilla.value.length<1){
		alert ("Debe ingresar el archivo de plantilla");
		return false;
	}

	if (document.formAdjuntarArchivos.archivoCsv.value.length<1){
		alert ("Debe ingresar el archivo CSV");
		return false;
	}

	if (document.formaTRD.tipo.value==0){
		alert ("Debe seleccionar el tipo de documento");
		return false;
	}

	return true;
}

function enviar() {

	if (!validar())
		return;

	document.formAdjuntarArchivos.accion.value="PRUEBA";
	document.formAdjuntarArchivos.submit();
}

</script>
<br/><br/><br/> 
<?
$phpsession = session_name()."=".session_id();
if(!$tipoRad) $tipoRad = 1;

//TRD
include "tipificar_masiva.php";

$params=$phpsession."&codiTRD=$codiTRD&depe_codi_territorial=$depe_codi_territorial&usua_nomb=$usua_nomb&depe_nomb=$depe_nomb&usua_doc=$usua_doc&tipo=$tipo&codusuario=$codusuario";
?>
<form action="adjuntar_masiva.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formAdjuntarArchivos">
<input type=hidden name=pNodo value='<?=$pNodo?>'>
<input type=hidden name=codProceso value='<?=$codProceso?>'>
<input type=hidden name=tipoRad value='<?=$tipoRad?>'>
<table width="70%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
	<tr align="center">
		<td height="25" class="titulos4" colspan="4">
			ADJUNTAR ARCHIVOS
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo return_bytes(ini_get('upload_max_filesize')); ?>">
			<input name="accion" type="hidden" id="accion">
		</td>
	</tr>
	<tr align="center">
		<td width="20%" class="titulos2">PLANTILLA </td>
		<td width="30%" height="30" class="listado2">
			<input name="archivoPlantilla" type="file" value='<?=$archivoPlantilla?>' class="tex_area"  id=archivoPlantilla accept="application/rtf">
		</td>
		<td width="20%" class="titulos2">CSV</td>
		<td height="30%" class="listado2">
			<input name="archivoCsv" type="file" class="tex_area" id=archivoCsv  value='<?=$archivoCsv?>'>
		</td>
	</tr>
	<tr align="center">
		<td height="30" colspan="4" class="listado2_center celdaGris">
            <blockquote>Nota.
                Esta operaci&oacute;n generar&aacute; un radicado por cada registro del
                archivo CSV de origen. Por favor tenga cuidado con esta opci&oacute;n ya
                que se realizar&aacute; cambios irreversibles en la base de datos.
                <input name="enviaPrueba" type="button" class="botones" id="envia22" onClick="enviar();" value="Enviar Prueba">
             </blockquote>   
        	</span>
        </td>
	</tr>
</table>
</form>
</body>
</html>
