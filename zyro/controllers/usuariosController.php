<?php
class usuariosController extends Controller{
	private $_usuario;

	public function __construct(){
		parent::__construct();
		$this->_usuario = $this->loadModel('usuarios');
	}

	public function index(){
		$this->_view->titulo = 'Usuarios';
		$this->_view->usuario = $this->_usuario->getUsuarios();
        $this->_view->renderizar('index', 'usuarios');
	}

    public function editar($id){
        //Session::acceso('admin');
        //Session::accesoEstricto(array('usuario'), true);

        if(!$this->filtrarInt($id)){
            $this->redireccionar('usuarios');
        }

        if(!$this->_usuario->getUsuario($this->filtrarInt($id))){
            $this->redireccionar('usuarios');
        }

        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getSql('nombre')){
                $this->_view->_error = 'Debe introducir su nombre';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error = 'Debe introducir su nombre usuario';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error = 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->validarEmail($this->getParam('email'))){
                $this->_view->_error = 'La direccion de email es inv&aacute;lida';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->_usuario->verificarEmail($this->getParam('email'))){
                $this->_view->_error = 'Esta direccion de correo ya esta registrada';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->getSql('password')){
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->getParam('password') != $this->getParam('confirmar')){
                $this->_view->_error = 'Los passwords no coinciden';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }

            $this->_usuario->setUsuario(
                $this->filtrarInt($id),
                $this->getSql('nombre'),
                $this->getAlphaNum('usuario'),
                $this->getSql('password'),
                $this->getParam('email')
                );
            $this->redireccionar('usuarios');
        }


        $this->_view->datos = $this->_curso->getCurso($this->filtrarInt($id));
        $this->_view->renderizar('editar', 'usuarios');
    }


    public function nuevo(){

        $this->_view->titulo = 'Registro';
        
        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getSql('nombre')){
                $this->_view->_error = 'Debe introducir su nombre';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error = 'Debe introducir su nombre usuario';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error = 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->validarEmail($this->getParam('email'))){
                $this->_view->_error = 'La direccion de email es inv&aacute;lida';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->_usuario->verificarEmail($this->getParam('email'))){
                $this->_view->_error = 'Esta direccion de correo ya esta registrada';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if(!$this->getSql('password')){
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            if($this->getParam('password') != $this->getParam('confirmar')){
                $this->_view->_error = 'Los passwords no coinciden';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            
            $this->_usuario->setUsuario(
                    $this->getSql('nombre'),
                    $this->getAlphaNum('usuario'),
                    $this->getSql('password'),
                    $this->getParam('email')
                    );
            
             if(!$this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error = 'Error al registrar el usuario';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
             }
             
            $this->_view->datos = false;
            $this->redireccionar('usuarios');
        }
        
        $this->_view->renderizar('nuevo', 'usuarios');
    }

    public function eliminar($id){
        //Session::accesoEstricto(array('usuario', true));

        if(!$this->filtrarInt($id)){
            $this->redireccionar('usuarios');
        }

        if(!$this->_usuario->getUsuario($this->filtrarInt($id))){
            $this->redireccionar('usuarios');
        }

        $this->_usuario->eliminarUsuario($this->filtrarInt($id));
        $this->redireccionar('usuarios');
    }
}
?>