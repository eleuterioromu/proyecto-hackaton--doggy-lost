<?php
class indexController extends Controller{
    private $_catalogo;
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        //todos los perros que 
        //$this->_view->catalogo = $this->_catalogo->getCatalogos();
        //echo "<pre>";
        //print_r($this->_view->catalogo);

        $this->_view->titulo = 'Portada';
        $this->_view->renderizar('index', 'inicio');
    }
}
?>