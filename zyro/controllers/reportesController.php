<?php
class reportesController extends Controller{
    private $_reportes;
    public function __construct(){
        parent::__construct();
        $this->_reportes = $this->loadModel('reportes');
    }
    
    public function index(){
        $this->_view->reportes = $this->_reportes->getReportes();
        $this->_view->titulo = 'Portada';

        $this->_view->renderizar('index', 'reportes');
    }

    public function nuevo(){
        $this->_view->titulo = 'nuevo';
        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;

            if(!$this->getAlphaNum('nombre')){
                $this->_view->_error = 'Debe introducir su nombre';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            if(!$this->getAlphaNum('apellidos')){
                $this->_view->_error = 'Debe introducir su nombre apellidos';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            if(!$this->validarEmail($this->getParam('email'))){
                $this->_view->_error = 'La direccion de email es inv&aacute;lida';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            if(!$this->getAlphaNum('telefono')){
                $this->_view->_error = 'Debe introducir su numero de telefono';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }
            if(!$this->getAlphaNum('direccion')){
                $this->_view->_error = 'Debe introducir su ubicacion';
                $this->_view->renderizar('nuevo', 'usuarios');
                exit;
            }

            $imagen = '';
            
            if(isset($_FILES['imagen']['name'])){
                $this->getLibrary('upload' . DS . 'class.upload');
                $ruta = ROOT . 'public' . DS . 'img' . DS . 'dog' . DS;
                $upload = new upload($_FILES['imagen'], 'fr_FR');
                $upload->allowed = array('image/*');
                $upload->file_new_name_body = 'upl_' . uniqid();
                $upload->process($ruta);
                
                if($upload->processed){
                    $imagen = $upload->file_dst_name;
                    $thumb = new upload($upload->file_dst_pathname);
                    $thumb->image_resize = true;
                    $thumb->image_x = 100;
                    $thumb->image_y = 70;
                    $thumb->file_name_body_pre = 'thumb_';
                    $thumb->process($ruta . 'thumb' . DS);
                }
                else{
                    $this->_view->assign('_error', $upload->error);
                    $this->_view->renderizar('nuevo', 'post');
                    exit;
                }
            }

            $this->_reportes->setReportes(
                    $this->getParam('nombre'),
                    $this->getParam('apellidos'),
                    $this->getParam('email'),
                    $this->getParam('telefono'),
                    $this->getParam('direccion'),
                    $this->getParam('id_estatus'),
                    $this->getParam('raza'),
                    $imagen,
                    $this->getParam('sexo'),
                    $this->getParam('descripcion')
                    );

            $this->_view->datos = false;
            $this->redireccionar('reportes');
        }
        $this->_view->renderizar('nuevo', 'reportes');
    }

}
?>