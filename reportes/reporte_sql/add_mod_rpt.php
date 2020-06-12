<?php 
if(is_file("../../aut_verifica.inc.php")) require_once("../../aut_verifica.inc.php");
else if(is_file("../../../aut_verifica.inc.php")) require_once("../../../aut_verifica.inc.php");

require_once ('../../bibliotecas/valida 1.0/valida.php');
require_once ('../../bibliotecas/valida 1.0/biblio.php');

require_once ('../../clases/bd/MySQLConex.php');	
require_once("../../xajax_0.2.4/xajax.inc.php");

require_once('../../clases/gui/select.php');

require_once ('../../clases/base/rpt_reporte.php'); 
$sel=new select();
$id_rpt=$_GET['id_rpt'];
$con = new MySQLConex();
$con->abrir("../../Connections/datos_conex.php");
if($_GET['id_rpt']!=NULL){
	
	$rpt=rpt_reporte::recuperar($con,$_GET['id_rpt']);
	
	if($rpt->est_rpt=='a')$js.="get('est_rpt1').checked=true;";
	else $js.="get('est_rpt2').checked=true;";
	
	$des_rpt=$rpt->des_rpt;
	$sql_rpt=$rpt->sql_rpt;
	$id_men=$rpt->id_men;
	$tipo_rpt=$rpt->tipo_rpt;

	$accion='mod';
	$titulo='Modificar ';
}else{
	$accion='add';
	$titulo='Nuevo ';
	$des_rpt='';
	$sql_rpt='';
}


$xajax = new xajax();
$xajax->registerExternalFunction("agregarReporte","xajax.php");
$xajax->registerExternalFunction("modificarReporte","xajax.php");

$xajax->processRequests();
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Cache-Control" content="no-cache" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?php $xajax->printJavascript("../../xajax_0.2.4/"); ?>
<title><?php echo $titulo ?> Reporte</title>
<link href="../../<? if (isset($_SESSION['hoja_estilos'])) echo $_SESSION['hoja_estilos']; else echo 'sapred.css'; ?>" rel="stylesheet" type="text/css" />
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
	function aceptar(){
		if(get('id_rpt').value==''){
			xajax_agregarReporte(xajax.getFormValues('frm'));
		}else{
			xajax_modificarReporte(xajax.getFormValues('frm'));
		}
	}

	
</script>
</head>
<body class="tablainterface" onLoad="get('des_rpt').focus();">
<center>	
<div style=" margin:3px;">
<form enctype="multipart/form-data" name="frm" id="frm">
 <table border="0" cellspacing="1"  cellpadding="0" align="center" width="90%"> <tr> <td align="left"><strong>Descripci&oacute;n:</strong>
       <input name="des_rpt" type="text" id="des_rpt"  size="40" <?php echo  validarCaracteres($expr['descripcion'],'','') ?> value="<?php echo $des_rpt ?>" >     </td>
     </tr>
   <tr>
     <td align="left"><strong>SQL:</strong><br>
<textarea name="sql_rpt" cols="80" rows="10" id="sql_rpt" style="width:100%px;"><?php echo stripslashes($sql_rpt) ?></textarea>     </td>
     </tr>
   <tr>
     <td align="left">
       <table  border="0" cellspacing="0" cellpadding="0">
         <tr>
         <td><strong>Estado:</strong></td>
           <td>
             <input name="est_rpt"  id="est_rpt1" type="radio"  style="border:none; background:none;" value="a" checked>
             </td>
           <td><strong>Activo</strong></td>
           <td>&nbsp;</td>
           <td>
             <input name="est_rpt" id="est_rpt2" type="radio" value="i"  style="border:none; background:none;">
             </td>
           <td><strong>Inactivo</strong></td>
           <td>
             <select id="tipo_rpt" name="tipo_rpt">
                <?php  
                    if ($tipo_rpt == 'E') {
                      ?>
                      <option value="E" >Excel</option>
                      <option value="G" >Grafica</option>
                      <?php
                    }elseif ($tipo_rpt == 'G') {
                      ?>
                      <option value="G" >Grafica</option>
                      <option value="E" >Excel</option>
                      <?php
                    }else{
                       ?>
                      <option value="E" >Excel</option>
                      <option value="G" >Grafica</option>
                      <?php
                    }
                ?>
             </select>
           </td>
           </tr>
       </table>     </td>
     </tr>
   <tr>
   <td align="center"><table width="100%" border="0">
     <tr>
       <td valign="top"><?
  require "../../Connections/datos_conex.php";
 $q="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME Like 'tipususis' OR  TABLE_NAME Like 'tipusuario' ) AND TABLE_SCHEMA='".$database_sapred_db."'";
 $res=$con->query($q);
 if($con->num_rows($res)!=0){
 	$fil=$con->fetch($res);
	$con->free($res);
	$tab=$fil[0];
	 $q="SELECT * FROM ".$tab;
	 if($accion=='mod'){
	 	$q.=" a LEFT JOIN rpt_reporte_usuario b ON b.id_rpt=$id_rpt AND b.tip_usu=a.tip_usu ";
	 }
	 $res=$con->query($q);
 
 ?>
         <table  border="0" cellspacing="0" cellpadding="0" class="bordeTabla">
           <tr class="filaTitulo">
             <th class="bordeCelda" colspan="4">Tipo de Usuario </th>
           </tr>
           <?php for($i=0;$fil=$con->fetch($res);$i++){?>
           <? if($i%2==0) { ?>
           <tr>
             <? } ?>
             <td class="bordeCelda"><?php echo $fil[1] ?></td>
             <td class="bordeCelda"><input type="checkbox" name="chk<?php echo $i ?>" value="<?php  echo $fil[0] ?>"  <?php if($fil[id_rpt]!='') echo 'checked'; ?> ></td>
             <? if($i%2!=0) { ?>
           </tr>
           <? } ?>
           <?php }
   
   ?>
         </table>
         <input name="tab" type="hidden" value="<? echo $tab ?>">
         <?php }?></td>
       <td valign="top"><?
  require "../../Connections/datos_conex.php";

	 $q="SELECT a.id_men, a.tit_men FROM  menus a where men_pad=6   order by tit_men";
	
	 $res=$con->query($q);
 
 ?>
         <table  border="0" cellspacing="0" cellpadding="0" class="bordeTabla">
           <tr class="filaTitulo">
             <th class="bordeCelda" colspan="4">Ubicaci&oacute;n en el men&uacute; Reportes</th>
           </tr>
           <?php for($i=0;$fil=$con->fetch($res);$i++){?>
           <? if($i%2==0) { ?>
           <tr>
             <? } ?>
             <td class="bordeCelda"><?php echo $fil[1] ?></td>
             <td class="bordeCelda"><input type="radio" name="id_men" value="<?php  echo $fil['id_men'] ?>"  <?php if($fil['id_men']==$id_men) echo 'checked'; ?> ></td>
             <? if($i%2!=0) { ?>
           </tr>
           <? } ?>
           <?php }
   
   ?>
         </table>
        </td>
     </tr>
   </table></td>
   </tr>
   <tr>
     <td align="center">
       <input name="add_preg" type="button" class="boton" id="add_preg" value="Aceptar" onClick="aceptar();">
       <input name="id_rpt" id="id_rpt" type="hidden" value="<?php echo $id_rpt ?>">
     </td>
   </tr>
 </table>
 
</form>	
</div>
</center>
<script>
<?php echo $js; ?>
</script>
</body>
</html>