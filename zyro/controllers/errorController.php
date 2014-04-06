<?php
class errorController extends Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->_view->titulo = "Error";
		$this->_view->mensaje = $this->_getError();
		$this->_view->renderizar('index');
	}

	public function access($codigo){
		$this->_view->titulo = 'Error';
		if(isset($codigo)){
			$this->_view->mensaje = $this->_getError($codigo);
			$this->_view->renderizar('access');
			exit;
		}
		$this->redireccionar('index');
	}

	public function _getError($codigo = false){
		if(isset($codigo)){
			$codigo = (int)$codigo;
			if(is_int($codigo)){
				$codigo = $codigo;
			}
			else{
				$codigo = 'default';
			}
		}
		$error['default'] = 'Ha ocurrido un error vuelva a intentar mas tarde';
		$error['5050'] = 'Acceso denegado';
		$error['8080'] = 'Tiempo de session agotado';

		if(!array_key_exists($codigo, $error)){
			return $error['default'];
		}else{
			return $error[$codigo];
		}
	}
}
?>