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
	
	$accion='mod';
	$titulo='Modificar ';
}
$xajax = new xajax();
$xajax->registerExternalFunction("modificarReporte","xajax.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<strong>SQL:</strong><br>
<textarea name="sql_rpt" cols="80" rows="10" id="sql_rpt" style="width:100%px;"><?php echo stripslashes($sql_rpt) ?></textarea>  
</body>
</html>