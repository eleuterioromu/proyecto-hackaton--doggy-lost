<?php
class loginController extends Controller{
	private $_login;

	public function __construct(){
		parent::__construct();
		$this->_login = $this->loadModel('login');
	}

    public function index()
    {
        if(Session::getSession('autenticado')){
            $this->redireccionar();
        }
        
        $this->_view->titulo = 'Iniciar Sesion';
        
        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error = 'Debe introducir su nombre de usuario';
                $this->_view->renderizar('index','login');
                exit;
            }
            
            if(!$this->getAlphaNum('pass')){
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('index','login');
                exit;
            }

            $row = $this->_login->getUsuario(
                    $this->getAlphaNum('usuario'),
                    $this->getAlphaNum('pass')
                    );
            
            if(!$row){
                $this->_view->_error = 'Usuario y/o password incorrectos';
                $this->_view->renderizar('index','login');
                exit;
            }
            
            if($row['estado'] != 1){
                $this->_view->_error = 'Este usuario no esta habilitado';
                $this->_view->renderizar('index','login');
                exit;
            }
                        
            Session::setSession('autenticado', true);
            Session::setSession('level', $row['role']);
            Session::setSession('usuario', $row['usuario']);
            //Session::setSession('id_usuario', $row['id']);
            //Session::setSession('tiempo', time());
            
            $this->redireccionar();
        }
        
        $this->_view->renderizar('index','login');
        
    }

    public function cerrar(){
        Session::destroySession();
        $this->redireccionar();
    }

    public function mostrar(){
        echo 'level:'. Session::getSession('level') . "<br/>";
        //echo 'user1:'. Session::getSession('user1'). "<br/>";
        //echo 'user2:'. Session::getSession('user2');
    }
}
?>