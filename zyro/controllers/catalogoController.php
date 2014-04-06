<?php
class catalogoController extends Controller{
    private $_catalogo;
    private $_utilidad;
    private $_clasificacion;
    public function __construct(){
        parent::__construct();
        $this->_catalogo = $this->loadModel('catalogo');
        $this->_utilidad = $this->loadModel('utilidad');
        $this->_clasificacion = $this->loadModel('clasificacion');
    }
    
    public function index(){
        $this->_view->catalogo = $this->_catalogo->getCatalogos();
        $this->_view->titulo = 'Portada';

        $this->_view->renderizar('index', 'catalogo');
    }
    
    public function catalogoId($id){
        $this->_view->catalogo = $this->_catalogo->getCatalogoId($id);
        $this->_view->titulo = 'Portada';
        $this->_view->renderizar('index', 'catalogo');
    }

    public function catalogoCategoria(){
        $this->_view->catalogo = $this->_catalogo->getCatalogoCategoria('policia', 'chico');

        $this->_view->titulo = 'Portada';
        $this->_view->renderizar('index', 'catalogo');
    }

    public function nuevo(){
        $this->_view->titulo = 'nuevo';
        

        if($this->getInt('guardar') == 1){
            $this->_view->datos = $_POST;            
        
            if(!$this->getTexto('raza')){
                $this->_view->_error = 'introducir raza';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('foto')){
                $this->_view->_error = 'introducir foto';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('sexo')){
                $this->_view->_error = 'macho o hembra';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('caracter')){
                $this->_view->_error = 'a';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('salud')){
                $this->_view->_error = 'a';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('cuidado')){
                $this->_view->_error = 'a';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }
            if(!$this->getTexto('categoria')){
                $this->_view->_error = 'a';
                $this->_view->renderizar('nuevo', 'cursos');
                exit;
            }

            $this->_catalogo->insertarPerro(
                    $this->getParam('raza'),
                    $this->getParam('foto'),
                    $this->getParam('sexo'),
                    $this->getParam('caracter'),
                    $this->getParam('salud'),
                    $this->getParam('cuidado'),
                    $this->getParam('categoria'),
                    $this->getParam('utilidad'),
                    $this->getParam('clasificacion')
                    );
            $this->redireccionar('catalogo');
        }

        $this->_view->renderizar('nuevo', 'catalogo');
    }


    public function eliminar($id){

        if(!$this->filtrarInt($id)){
            $this->redireccionar('catalogo');
        }

        if(!$this->_catalogo->getCatalogoId($this->filtrarInt($id))){
            $this->redireccionar('catalogo');
        }

        $this->_catalogo->eliminarPerro($this->filtrarInt($id));
        $this->redireccionar('catalogo');
    }
}
?>