<?php
if(is_file("../../aut_verifica.inc.php")) require_once("../../aut_verifica.inc.php");
else if(is_file("../../../aut_verifica.inc.php")) require_once("../../../aut_verifica.inc.php");

require_once('../../clases/base/rpt_reporte.php');
$nivel_acceso=10; 
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
	{
	header ("Location: $redir?error_login=5");
	exit;
	}
?>
<?php 
require_once '../../clases/bd/MySQLConex.php';
require_once '../../clases/base/rpt_reporte.php';
require("../../xajax_0.2.4/xajax.inc.php");
$xajax = new xajax();

$xajax->registerFunction("listareportes");

$xajax->processRequests();
?>
<html>
<head>
<title>.: SAPRED :. Sistema administrador de procesos educativos ... www.siticol.com ...</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="../../bibliotecas/submodal/subModal.css" rel="stylesheet" type="text/css">
<link href="../../bibliotecas/submodal/style.css" rel="stylesheet" type="text/css">
<link href="../../sapred4.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../bibliotecas/submodal/common.js"></script>
<script language="javascript" src="../../bibliotecas/submodal/subModal.js"></script>
<link rel="stylesheet" type="text/css" href="../../bibliotecas/dhtmltooltip/dhtmltooltip.css">
<script type="text/javascript" src="../../bibliotecas/js/general.js"></script>
<script>
function get(id){
	return document.getElementById(id);
}
function mostrar(id){
	get(id).style.display='';
}
function ocultar(id){
	get(id).style.display='none';
}
</script>
<?php $xajax->printJavascript("../../xajax_0.2.4/"); ?>
</head>
<body>
<table width="520" border="0" cellpadding="0" cellspacing="0" class="tablainterface">
  <tr>
    <td width="30" class="tbsup1" nowrap="nowrap">&nbsp;</td>
    <td width="430" class="tbsuptxt">
      <div align="center">Gestion de Reportes </div>
    </td>
    <td width="30" class="tbsup2">&nbsp;</td>
  </tr>
  <tr>
    <td width="30" class="tbcen1">&nbsp;</td>
    <td width="430" nowrap>
	 <div id="ppal" align="center">
	<br>
	
	<br><div id="lista"></div><br>
	<input name="btn1" id="btn1" type="button" value="Nuevo" class="boton" onClick="showPopWin('add_mod_rpt.php',520,320);">
	<br>
	</div>
      <table  id="tblProc" border="0" cellpadding="2" align="center" style="margin:10px; display:none">
          <tr>
            <td valign="middle"><img src="loader01.gif" alt="..."></td>
            <td>&nbsp;... Por favor espere un momento.</td>
          </tr>
      </table>
	</td>
    <td width="30" class="tbcen2">&nbsp;</td>
  </tr>
  <tr>
    <td width="30" class="tbinf1">&nbsp;</td>
    <td width="430" class="tbinfcent" nowrap="nowrap">&nbsp;</td>
    <td width="30" class="tbinf2" nowrap="nowrap">&nbsp;</td>
  </tr>
</table>

<?php
function listaReportes(){
	$xres=new xajaxResponse();
	
	$con = new MySQLConex();
	try{
		$con->abrir("../../Connections/datos_conex.php");
	}catch(Exception $e){
		die(header ("Location:  $redir?error_login=0"));
	}
	ob_start();
	$lista=rpt_reporte::getFilteredBy($con);
	if(count($lista)>0){
?>
		<table width="99%" border="0" cellspacing="0" cellpadding="0" class="bordeTabla">
		  <tr>
			<td align="center"  class="bordeCelda"><strong>Reporte</strong></td>
			<td align="center" class="bordeCelda"><strong>Estado</strong></td>
			<td align="center" class="bordeCelda" width="20%"><strong>Acciones</strong></td>
		  </tr>
		
		<?php	foreach($lista as $rep){	?>
		  <tr>
			<td align="left" class="bordeCelda"><?php echo $rep->des_rpt ?>&nbsp;</td>
			<td align="center" class="bordeCelda"><?php if($rep->est_rpt=='a') echo 'Activa'; else echo 'Inactiva'; ?>&nbsp;</td>
			<td align="center" class="bordeCelda"><img src="../../presentacion/icons/fam/application_form_edit.png" alt="M" width="16" height="16" title="Modificar" style="cursor:pointer;" onClick="showPopWin('add_mod_rpt.php?id_rpt=<?php echo $rep->id_rpt ?>',520,320);" align="middle">
			<img src="../../images/3puntos.png"  alt="P" height="16" align="middle"  title="Parametros" style="cursor:pointer;" onClick="showPopWin('add_mod_par.php?id_rpt=<?php echo $rep->id_rpt ?>',400,260);">
			</td>
		</tr>
		<?php	}	?>
		</table>
<?php 
	}else{
	?>
	<div><strong>No se encontraron reportes registrados</strong></div>
	<?php	
	}
	$html=ob_get_clean();
	
	
	$xres->addAssign('lista','innerHTML',$html);
	$xres->addScript("ocultar('tblProc');mostrar('lista');");
	return utf8_encode($xres->getXML());
	
} 
?>
<script>xajax_listareportes();</script>
</body>
</html>

