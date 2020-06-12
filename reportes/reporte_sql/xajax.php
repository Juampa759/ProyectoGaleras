<?php 
require_once('../../clases/gui/select.php');

require_once('../../clases/base/rpt_reporte.php');
require_once('../../clases/base/rpt_reporteparametro.php');
require_once('../../clases/base/rpt_reporteparametrovalor.php');

function agregarReporte($form){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	
	$des_rpt=utf8_decode($form['des_rpt']);
	$est_rpt=utf8_decode($form['est_rpt']);
	$id_men=utf8_decode($form['id_men']);
	$sql_rpt=addslashes(utf8_decode($form['sql_rpt']));
	$tipo_rpt=utf8_decode($form['tipo_rpt']);
	if($id_men=='')$id_men='0';
	$obj= rpt_reporte::crear($con ,$des_rpt, $sql_rpt, $est_rpt,$id_men, $tipo_rpt);
	$xres=new xajaxResponse();
	if($obj->id_rpt!=NULL){
		if($form['tab']!=''){
			$x=0;
			foreach($form as $cam=>$val){
				if(stripos($cam,'chk')!==FALSE){
					$arr[$x]=$val;
					$x++;
				}
			}
			$obj->agregarUsuario($con,$arr);
		}
		$xres->addScript("parent.xajax_listareportes();parent.hidePopWin();");
	}else{
		$xres->addAlert("Error al registrar el reporte");
	}
	return utf8_encode($xres->getXML());
}

function modificarReporte($form){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	$obj= rpt_reporte::recuperar($con , $form['id_rpt']);
	
	$obj->des_rpt=utf8_decode($form['des_rpt']);
	$obj->est_rpt=utf8_decode($form['est_rpt']);
	$obj->id_men=utf8_decode($form['id_men']);
	$obj->sql_rpt=addslashes(utf8_decode($form['sql_rpt']));
	$obj->tipo_rpt=utf8_decode($form['tipo_rpt']);
	$xres=new xajaxResponse();
	try{
		$obj->setConex($con);
		$obj->actualizar();
		if($form['tab']!=''){
			$x=0;
			foreach($form as $cam=>$val){
				if(stripos($cam,'chk')!==FALSE){
					$arr[$x]=$val;
					$x++;
				}
			}
			$obj->agregarUsuario($con,$arr,'mod');
		}
		$xres->addScript("parent.xajax_listareportes();");
		$xres->addScript("parent.hidePopWin();");
	}
	catch(Exception $e){
		$xres->addAlert("Error al actualizar reporte ".$e->getMessage());
	}
	
	return utf8_encode($xres->getXML());
}

function agregarParametro($frm){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	
	$des_rpar=utf8_decode($frm['des_rpar']);
	$tip_rpar=utf8_decode($frm['tip_rpar']);
	$id_rpt=utf8_decode($frm['id_rpt']);
	try{
		
		$obj= rpt_reporteparametro::crear($con , $id_rpt, $des_rpar,$tip_rpar,'a');
		if($obj->id_rpar!=NULL){
			include_once("add_mod_par.php");
			return listaParametros($id_rpt);
		}else{
			throw(new Exception('Error'));
		}
	}catch(Exception $e){
		$xres=new xajaxResponse();
		$xres->addAlert('Error al registrar parametro'.$e->getMessage());
		return utf8_encode($xres->getXML());
	}
}
function modificarParametro($frm){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	try{
		$des_tem=utf8_decode($des_tem);
		$obj= rpt_reporteparametro::recuperar($con , $frm['id_rpar']);
		$obj->des_rpar=utf8_decode($frm['des_rpar']);
		$obj->tip_rpar=utf8_decode($frm['tip_rpar']);
		$obj->est_rpar=utf8_decode($frm['est_rpar']);
		$id_rpt=$frm['id_rpt'];
		$obj->setConex($con);
		$obj->actualizar();
		include_once("add_mod_par.php");
		return listaParametros($id_rpt);
	}catch(Exception $e){
		$xres=new xajaxResponse();
		$xres->addAlert($e->getMessage().'Error al actualizar parametro');
		return utf8_encode($xres->getXML());
	}
	
}
function cargarDatosParametro($id_rpar){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	$xres=new xajaxResponse();
	try{
		$obj= rpt_reporteparametro::recuperar($con , $id_rpar);
		$xres->addAssign('des_rpar','value',$obj->des_rpar);
		$xres->addAssign('tip_rpar','value',$obj->tip_rpar);
		if($obj->est_rpar=='a')
			$xres->addAssign('est_rpar1','checked','true');
		else
			$xres->addAssign('est_rpar2','checked','true');
		$xres->addAssign('id_rpar','value',$obj->id_rpar);
	}catch(Exception $e){
		$xres->addAlert('Error al actualizar parametro');
	}
	return utf8_encode($xres->getXML());
}

function agregarValor($frm, $t){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	
	$val_rpv=utf8_decode($frm['val_rpv']);
	$tit_rpv=utf8_decode($frm['tit_rpv']);
	$var_rpv=utf8_decode($frm['var_rpv']);
	$id_rpar=utf8_decode($frm['id_rpar']);
	$sql_rpv=stripslashes(utf8_decode($frm['sql_rpv']));
	
	try{
		$obj= rpt_reporteparametrovalor::crear($con , $id_rpar,$val_rpv, $tit_rpv,$var_rpv,addslashes($sql_rpv));
	
		if($obj->id_rpv!=NULL){
			include_once("add_mod_par.php");
			return listaValores($id_rpar,$t);
		}else{
			throw(new Exception('Error'));
		}
	}catch(Exception $e){
		$xres=new xajaxResponse();
		$xres->addAlert('Error al registrar valor'.$e->getMessage());
		return utf8_encode($xres->getXML());
	}
	
}
function modificarValor($frm,$t){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	try{
		$id_rpv=utf8_decode($frm['id_rpv']);
		$obj= rpt_reporteparametrovalor::recuperar($con , $id_rpv);
		$sql_rpv=stripslashes(utf8_decode($frm['sql_rpv']));
		$obj->val_rpv=utf8_decode($frm['val_rpv']);
		$obj->tit_rpv=utf8_decode($frm['tit_rpv']);
		$obj->var_rpv=utf8_decode($frm['var_rpv']);
		$obj->sql_rpv=addslashes($sql_rpv);
		$obj->setConex($con);
		$obj->actualizar();
		include_once("add_mod_par.php");
		return listaValores($frm['id_rpar'],$t);
	}catch(Exception $e){
		$xres=new xajaxResponse();
		$xres->addAlert('Error al agregar parametro'.$e->getMessage());
		return utf8_encode($xres->getXML());
	}
}
function cargarDatosValor($id_rpv){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	$xres=new xajaxResponse();
	try{
		$obj= rpt_reporteparametrovalor::recuperar($con , $id_rpv);
		$xres->addAssign('val_rpv','value',$obj->val_rpv);
		$xres->addAssign('tit_rpv','value',$obj->tit_rpv);
		$xres->addAssign('var_rpv','value',$obj->var_rpv);
		$xres->addAssign('id_rpv','value',$obj->id_rpv);
		$xres->addAssign('sql_rpv','value',stripslashes($obj->sql_rpv));
		
	}catch(Exception $e){
		$xres->addAlert('Error al actualizar parametro');
	}
	return utf8_encode($xres->getXML());
}

function eliminarValor($id_rpv,$t){
	try{
		$con = new MySQLConex();
		$con->abrir('../../Connections/datos_conex.php');
	}
	catch(Exception $e){
		echo "Error al conectarse a la base de datos.";
	}
	
	try{
		$obj= rpt_reporteparametrovalor::recuperar($con , $id_rpv);
		$id_rpar=$obj->id_rpar;
		$obj->setConex($con);
		$obj->eliminar();
		include_once("add_mod_par.php");
		return listaValores($id_rpar,$t);
	}catch(Exception $e){
		$xres=new xajaxResponse();
		$xres->addAlert('Error al eliminar valor'.$e->getMessage());
		return utf8_encode($xres->getXML());
	}
}


?>