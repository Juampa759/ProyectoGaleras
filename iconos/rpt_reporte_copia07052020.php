<?php
/* Bosquejo de Clase generado con ClassFactory 0.1 Beta   */
class rpt_reporte {
	private $con;
	private $id_rpt;
	private $des_rpt;
	private $sql_rpt;
	private $est_rpt;
	private $id_men;
	public function setConex(BdConex $con){
		$this->con = $con;
	}

	public function __construct($id_rpt, $des_rpt, $sql_rpt, $est_rpt,$id_men){
		$this->id_rpt = $id_rpt;
		$this->des_rpt = $des_rpt;
		$this->sql_rpt = $sql_rpt;
		$this->est_rpt = $est_rpt;
		$this->id_men = $id_men;
	}

	public static function recuperar(BdConex $con, $id_rpt){
		$query = "SELECT * FROM rpt_reporte WHERE id_rpt = $id_rpt ";
		try{
			$res = $con->query($query);
			$fila = $con->fetch($res);
			$id_rpt = $id_rpt;
			$des_rpt = $fila['des_rpt'];
			$sql_rpt = $fila['sql_rpt'];
			$est_rpt = $fila['est_rpt'];
			$id_men = $fila['id_men'];
			return new rpt_reporte($id_rpt, $des_rpt, $sql_rpt, $est_rpt, $id_men);
		}catch(Exception $e){
			throw($e);
			return NULL;
		}
	}
	public static function crear(BdConex $con , $des_rpt, $sql_rpt, $est_rpt,$id_men){
		$query = "
		INSERT INTO rpt_reporte SET 
		des_rpt = '$des_rpt', 
		sql_rpt = '$sql_rpt', 
		est_rpt = '$est_rpt', 
		id_men = '$id_men'
		";
		try{
			$con->query($query);
			$id_rpt = $con->insertId();
			return new rpt_reporte($id_rpt, $des_rpt, $sql_rpt, $est_rpt,$id_men);
		}catch(Exception $e){
			throw($e);
			return NULL;
		}
	}
	public function actualizar(){
		$con = $this->con;
		$query = "
		UPDATE rpt_reporte SET 
		des_rpt = '$this->des_rpt', 
		sql_rpt = '$this->sql_rpt', 
		est_rpt = '$this->est_rpt',
		id_men = '$this->id_men'
		WHERE 
		id_rpt = $this->id_rpt 
		";
		try{
			$con->query($query);
		}catch(Exception $e){
			throw($e);
		}
	}
	public function eliminar(){
		$con = $this->con;
		$query = "
		DELETE FROM rpt_reporte 
		WHERE 
		id_rpt = $this->id_rpt 
		";
		try{
			$con->query($query);
		}catch(Exception $e){
			throw($e);
		}
	}
	public function __get($var){
		switch($var){
			case('id_rpt'): return ($this->id_rpt); break;
			case('des_rpt'): return ($this->des_rpt); break;
			case('sql_rpt'): return ($this->sql_rpt); break;
			case('est_rpt'): return ($this->est_rpt); break;
			case('id_men'): return ($this->id_men); break;
		}
	}
	public function __set($var,$val){
		switch($var){
			case('des_rpt'): $this->des_rpt = $val; break;
			case('sql_rpt'): $this->sql_rpt = $val; break;
			case('est_rpt'): $this->est_rpt = $val; break;
			case('id_men'): $this->id_men = $val; break;
		}
	}
	public function getKey(){
		return "$this->id_rpt";
	}
	public function __toString(){
		return "";
	}
	public function getLabel(){
		return $this->des_rpt;
	}
	
	public static function getFilteredBy(BdConex $con, $est_rpt='%' ){
		$query = "SELECT * FROM rpt_reporte  ";
		if( $est_rpt!='%' ){
			$query.=" WHERE est_rpt='$est_rpt'  "; 
		}
		$query.=" ORDER BY  des_rpt";
		try{
			$res = $con->query($query);
			for($i=0;$fila = $con->fetch($res);$i++){
				$id_rpt = $fila['id_rpt'];
				$des_rpt = $fila['des_rpt'];
				$sql_rpt = $fila['sql_rpt'];
				$est_rpt = $fila['est_rpt'];
				$id_men = $fila['id_men'];
				$arr[$i]=new rpt_reporte($id_rpt, $des_rpt, $sql_rpt, $est_rpt ,$id_men);
			}
			return $arr;
		}catch(Exception $e){
			throw($e);
			return NULL;
		}
	}
	
	public function agregarUsuario(BdConex $con, $arr ,$ac='a'){
		try{
			if($ac!='a'){
				$query = "DELETE FROM rpt_reporte_usuario WHERE id_rpt=$this->id_rpt ; ";
				$con->query($query);
			}
			if(count($arr)>0){
				$query ='INSERT INTO rpt_reporte_usuario(id_rpt,tip_usu) VALUES';
				$s='';
				foreach($arr as $t_u){
					$query.=$s."($this->id_rpt,$t_u) ";
					$s=', ';				
				}
				$res = $con->query($query);
			}
			return true;
		}catch(Exception $e){
			throw($e);
		}
		return false;
	}
	
	public static function getByTipoUsuario(BdConex $con, $est_rpt='%', $tip_usu,$id_men='%'){
		$query = "SELECT r.* FROM rpt_reporte  r
		inner join rpt_reporte_usuario u on u.id_rpt=r.id_rpt AND tip_usu=$tip_usu ";
		$s=' WHERE ';
		if( $est_rpt!='%' ){
			$query.=$s." est_rpt='$est_rpt'  "; $s=' AND ';
		}
		if( $id_men!='%' ){
			$query.=$s." id_men=$id_men  "; $s=' AND ';
		}
		$query.=" ORDER BY  des_rpt";
		try{
			$res = $con->query($query);
			for($i=0;$fila = $con->fetch($res);$i++){
				$id_rpt = $fila['id_rpt'];
				$des_rpt = $fila['des_rpt'];
				$sql_rpt = $fila['sql_rpt'];
				$est_rpt = $fila['est_rpt'];
				$id_men = $fila['id_men'];
				$arr[$i]=new rpt_reporte($id_rpt, $des_rpt, $sql_rpt, $est_rpt,$id_men);
			}
			return $arr;
		}catch(Exception $e){
			throw($e);
			return NULL;
		}
	}
}

?>