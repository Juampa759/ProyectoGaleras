<?php 
if(is_file("../../aut_verifica.inc.php")) require_once("../../aut_verifica.inc.php");
else if(is_file("../../../aut_verifica.inc.php")) require_once("../../../aut_verifica.inc.php");

require_once ('../../bibliotecas/valida 1.0/valida.php');
require_once ('../../bibliotecas/valida 1.0/biblio.php');

require_once ('../../clases/bd/MySQLConex.php');	
require_once("../../xajax_0.2.4/xajax.inc.php");

require_once('../../clases/gui/select.php');

 
require_once('../../clases/base/rpt_reporte.php');
require_once('../../clases/base/rpt_reporteparametro.php');
require_once('../../clases/base/rpt_reporteparametrovalor.php');

$sel=new select();
$id_rpt=$_REQUEST['id_rpt'];


$xajax = new xajax();
$xajax->registerExternalFunction("agregarParametro","xajax.php");
$xajax->registerExternalFunction("modificarParametro","xajax.php");
$xajax->registerExternalFunction("cargarDatosParametro","xajax.php");

$xajax->registerExternalFunction("agregarValor","xajax.php");
$xajax->registerExternalFunction("modificarValor","xajax.php");
$xajax->registerExternalFunction("eliminarValor","xajax.php");
$xajax->registerExternalFunction("cargarDatosValor","xajax.php");


$xajax->registerFunction("listaParametros");
$xajax->registerFunction("listaValores");

$xajax->processRequests();
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Cache-Control" content="no-cache" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?php $xajax->printJavascript("../../xajax_0.2.4/"); ?>
<title>Parametros del Reporte</title>
<link href="../../<? if (isset($_SESSION['hoja_estilos'])) echo $_SESSION['hoja_estilos']; else echo 'sapred.css'; ?>" rel="stylesheet" type="text/css" />
<link href="../../bibliotecas/calendar/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../bibliotecas/valida 1.0/valida.js"></script>
<script language="javascript">

	function get(id){
		return document.getElementById(id);
	}
	function mostrar(id){
		get(id).style.display='';
	}
	function ocultar(id){
		get(id).style.display='none';
	}
	function validar(){
	}
	
	function aceptar1(){
		if(get('des_rpar').value=='') {
			alert('digite la descripcion');return false;
		}
		if(get('id_rpar').value==''){
			xajax_agregarParametro(xajax.getFormValues('frm1'));
		}else{
			xajax_modificarParametro(xajax.getFormValues('frm1'));
		}
	}
	function aceptar2(){
		if(get('filsql').style.display=='') tip='s';
		else tip='';
		if(get('id_rpv').value==''){
			xajax_agregarValor(xajax.getFormValues('frm2'),tip);
		}else{
			xajax_modificarValor(xajax.getFormValues('frm2'),tip);
		}
	}

	
</script>
</head>
<body class="tablainterface" onLoad="xajax_listaParametros(<?php echo $id_rpt ?>);get('des_rpar').focus();">
<center>	
<div style=" margin:3px;" id="ppal">
<form enctype="multipart/form-data" name="frm1" id="frm1">
 <table border="0" cellspacing="1"  cellpadding="0" align="center">
   <tr>
     <td width="25%"><strong>Descripci&oacute;n</strong></td>
     <td>
       <input name="des_rpar" type="text" id="des_rpar" style="width:200px;" size="40" <?php echo  validarCaracteres($expr['descripcion'],'','') ?>>
     </td>
   </tr>
   <tr>
     <td><strong>Tipo:</strong></td>
     <td>
       <select name="tip_rpar" id="tip_rpar">
         <option value="t" selected>Texto</option>
         <option value="n">Numero</option>
         <option value="f">Fecha</option>
         <option value="s">Seleccion</option>
       </select>
   </tr>
    <tr>
     <td><strong>Estado:</strong></td>
     <td>
       <table  border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td>
             <input name="est_rpar"  id="est_rpar1" type="radio"  style="border:none; background:none;" value="a" checked>
           </td>
           <td><strong>Activo</strong></td>
           <td>&nbsp;</td>
           <td>
             <input name="est_rpar" id="est_rpar2" type="radio" value="i"  style="border:none; background:none;">
           </td>
           <td><strong>Inactivo</strong></td>
         </tr>
       </table>
     </td>
   </tr>
   <tr>
     <td colspan="2" align="center">
       <input name="add_rpar" type="button" class="boton" id="add_rpar" value="Aceptar" onClick="aceptar1();">
       <input name="id_rpt" id="id_rpt" type="hidden" value="<?php echo $id_rpt ?>">
       <input name="id_rpar" id="id_rpar" type="hidden">
     </td>
   </tr>
 </table>
</form>	
<div id="lista_param"></div>
</div>
<table  id="tblProc" border="0" cellpadding="2" align="center" style="margin:10px; display:none">
  <tr>
	<td valign="middle"><img src="loader01.gif" alt="..."></td>
	<td>&nbsp;... Por favor espere un momento.</td>
  </tr>
</table>
<div id="ppal2" style="margin:3px;display:none">
<form enctype="multipart/form-data" name="frm2" id="frm2">
  <a href="javascript:ocultar('ppal2');mostrar('ppal');">Volver</a>
  <table border="0" cellspacing="1"  cellpadding="0" align="center">
   <tr>
     <td width="25%"><strong>Valor:</strong></td>
     <td>
       <input name="val_rpv" type="text" id="val_rpv" style="width:200px;" size="40" <?php echo  validarCaracteres($expr['descripcion'],'','') ?>>
     </td>
   </tr>
   <tr>
     <td><strong>Titulo</strong>:</td>
     <td>
       <input name="tit_rpv" type="text" id="tit_rpv" style="width:200px;" size="40" <?php echo  validarCaracteres($expr['descripcion'],'','') ?>>
     </td>
   </tr>
   <tr>
     <td><strong>Variable:</strong></td>
     <td>
       <input name="var_rpv" type="text" id="var_rpv" style="width:200px;" size="40" <?php echo  validarCaracteres($expr['descripcion'],'','') ?>>
     </td>
   </tr>
   <tr id="filsql" style="display:none">
     <td><strong title="debe contener 2 valores id (el valor) y label (la etiqueta) ">SQL:</strong></td>
     <td>
       <textarea name="sql_rpv" type="text" id="sql_rpv" style="width:200px;" size="40" rows="4" ></textarea>
     </td>
   </tr>
   <tr>
     <td colspan="2" align="center">
       <input name="add_vrp" type="button" class="boton" id="add_vrp" value="Aceptar" onClick="aceptar2();">
       <input name="id_rpar" id="id_rpar" type="hidden" >
       <input name="id_rpv" id="id_rpv" type="hidden">
     </td>
   </tr>
 </table>
 <div id="lista_vars"></div>
</form>	
</div>
</center>
<script>
<?php echo $js; ?>
</script>

<?php 
function listaParametros($id_rpt){
	$xres=new xajaxResponse();
	
	$con = new MySQLConex();
	try{
		$con->abrir("../../Connections/datos_conex.php");
	}catch(Exception $e){
		die(header ("Location:  $redir?error_login=0"));
	}
	ob_start();
	$lista=rpt_reporteparametro::getFilteredBy($con,$id_rpt);
	if(count($lista)>0){
?>
		<table width="99%" border="0" cellspacing="0" cellpadding="0" class="bordeTabla">
		  <tr>
			<td align="center"  class="bordeCelda"><strong>Descripcion</strong></td>
			<td align="center" class="bordeCelda"><strong>Tipo</strong></td>
			<td align="center" class="bordeCelda"><strong>Estado</strong></td>
			<td align="center" class="bordeCelda" width="20%"><strong>Acciones</strong></td>
		  </tr>
		
		<?php	foreach($lista as $par){	?>
		  <tr>
			<td align="left" class="bordeCelda"><?php echo $par->des_rpar ?>&nbsp;</td>
			<td align="center" class="bordeCelda"><?php if($par->tip_rpar=='t') echo 'Texto';
			 else if($par->tip_rpar=='n') echo 'N&uacute;mero';
			 else if($par->tip_rpar=='f') echo 'Fecha';
			 else echo 'Selecci&oacute;n'; ?>&nbsp;</td>
			 <td align="center" class="bordeCelda"><?php if($par->est_rpar=='a') echo 'Activo';
			 else echo 'Inactivo'; ?>&nbsp;</td>
			<td align="center" class="bordeCelda">
			<img src="../../presentacion/icons/fam/application_form_edit.png" alt="M" width="16" height="16" title="Modificar" style="cursor:pointer;" onClick="xajax_cargarDatosParametro(<?php echo $par->id_rpar ?>);" align="middle">
			<img src="../../images/3puntos.png" alt="V" height="16" title="Valores" style="cursor:pointer;" onClick="ocultar('ppal');ocultar('ppal2');mostrar('tblProc');xajax_listaValores(<?php echo $par->id_rpar ?>,'<?php echo $par->tip_rpar ?>');" align="middle">
		 </tr>
		<?php	}	?>
		</table>
<?php 
	}else{
	?>
	<div><strong>No se encontraron parametros registrados</strong></div>
	<?php	
	}
	$html=ob_get_clean();
	
	$xres->addAssign('lista_param','innerHTML',$html);
	$xres->addScript("ocultar('tblProc');mostrar('lista_param');");
	$xres->addAssign('id_rpar','value','');
	return utf8_encode($xres->getXML());
	
} 

function listaValores($id_rpar,$tip_rpar){
	$xres=new xajaxResponse();
	
	$con = new MySQLConex();
	try{
		$con->abrir("../../Connections/datos_conex.php");
	}catch(Exception $e){
		die(header ("Location:  $redir?error_login=0"));
	}
	ob_start();
	$lista=rpt_reporteparametrovalor::getFilteredBy($con,$id_rpar);
	if(count($lista)>0){
?>
		<table width="99%" border="0" cellspacing="0" cellpadding="0" class="bordeTabla">
		  <tr>
			<td align="center"  class="bordeCelda"><strong>Titulo</strong></td>
			<td align="center" class="bordeCelda"><strong>Variable</strong></td>
			<td align="center" class="bordeCelda"><strong>Valor</strong></td>
			<td align="center" class="bordeCelda" width="20%"><strong>Acciones</strong></td>
		  </tr>
		
		<?php	foreach($lista as $par){ ?>
		  <tr>
			<td align="left" class="bordeCelda"><?php echo $par->tit_rpv ?>&nbsp;</td>
			<td align="center" class="bordeCelda"><?php echo $par->var_rpv ?>&nbsp;</td>
			<td align="center" class="bordeCelda"><?php echo $par->val_rpv ?>&nbsp;</td>
			<td align="center" class="bordeCelda">
			<img src="../../presentacion/icons/fam/delete.png"  alt="E" height="16" align="middle"  title="Eliminar" style="cursor:pointer;" onClick="xajax_eliminarValor(<?php echo $par->id_rpv ?>,'<?php echo $tip_rpar ?>')">
			<img src="../../presentacion/icons/fam/application_form_edit.png" alt="M" width="16" height="16" title="Modificar" style="cursor:pointer;" onClick="xajax_cargarDatosValor(<?php echo $par->id_rpv ?>)" align="middle">
		 </tr>
		<?php	}	?>
		</table>
<?php 
	}else{
	?>
	<div><strong>No se encontraron valores registrados</strong></div>
	<?php	
	}
	$html=ob_get_clean();
	if($tip_rpar=='s')$xres->addscript("mostrar('filsql');");
	else $xres->addscript("ocultar('filsql');");
	
	$xres->addAssign('lista_vars','innerHTML',$html);
	$xres->addScript("ocultar('tblProc');mostrar('ppal2');");
	$xres->addScript("get('frm2').id_rpar.value='$id_rpar';get('val_rpv').value='';get('id_rpv').value='';get('tit_rpv').value='';get('sql_rpv').value='';get('var_rpv').value='';");
	return utf8_encode($xres->getXML());

} 
?>
</body>
</html>