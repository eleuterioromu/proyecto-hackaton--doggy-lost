<h2>Caracteristicas del Perro</h2>

<h2>nuevo perro</h2>

<form id="form2" method="POST" action="<?php echo BASE_URL; ?>reportes/nuevo">
	<input type="hidden" name="guardar" value="1">

	<p>Raza: <br/>
	<select name="raza">
		<option value ="terriers">Terriers</option>
		<option value="doberman">doberman</option>
	</select>
	</p>

	<p>Imagen: <br/><input type="text" name="foto" value="<?php if(isset($this->datos['foto'])) echo $this->datos['foto']; ?>"></p>

	<p>sexo: <br/>
	<select name="sexo">
		<option value ="macho">Macho</option>
		<option value="hembra">Hembra</option>
	</select>
	</p>

	<input type="hidden" name="caracter" value="null">
	<input type="hidden" name="salud" value="null">
	<input type="hidden" name="cuidado" value="null">
	<input type="hidden" name="categoria" value="null">
	
	<p>Categoria de perro:</br>
	<select name="utilidad">
		<option value ="1">policia</option>
		<option value ="2">compañia</option>
	</select>
	</p>
	
	<p>Tamaño:<br/>
	<select name="clasificacion">
		<option value ="1">chico</option>
		<option value ="2">mediano</option>
		<option value ="3">grande</option>
	</select>
	</p>

<input type="submit" class="button" value="enviar"></p>

</form>




<h2>Reportes</h2>
<h2>Datos Personales</h2>

<form name="form1" method="POST" action="<?php echo BASE_URL; ?>reportes/nuevo">
	<input type="hidden" name="enviar" value="1">
	<p><label>Nombre:</label><input type="text" name="nombre" value="<?php if(isset($this->datos)) echo $this->datos['nombre']; ?>"></p>

	<p><label>Apellidos:</label><input type="text" name="apellidos" value="<?php if(isset($this->datos)) echo $this->datos['apellidos']; ?>"></p>
	
	<p><label>email:</label><input type="text" name="email" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>"></p>

	<p><label>Telefono:</label><input type="rext" name="telefono" value=""></p>
	
	<p><label>Direccion:</label><input type="text" name="direccion" value=""></p>

<h2>Tipo de reporte</h2>
	<p>Estado: <br/>
	<select name="id_estatus">
		<option value ="1">Perdido</option>
		<option value="2">Encontrado</option>
	</select>
	</p>

	<input type="submit" class="button" value="enviar"></p>

</form>



<h2>Reportes</h2>
<h2>Datos Personales</h2>

<form name="form1" method="POST" action="<?php echo BASE_URL; ?>reportes/nuevo">
	<input type="hidden" name="enviar" value="1">
	<p><label>Nombre:</label><input type="text" name="nombre" value="<?php if(isset($this->datos)) echo $this->datos['nombre']; ?>"></p>

	<p><label>Apellidos:</label><input type="text" name="apellidos" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>"></p>
	
	<p><label>email:</label><input type="text" name="email" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>"></p>

	<p><label>Telefono:</label><input type="rext" name="telefono" value=""></p>
	
	<p><label>Direccion:</label><input type="text" name="direccion" value=""></p>

<h2>Tipo de reporte</h2>
	<p>Estado: <br/>
	<select name="id_estatus">
		<option value ="1">Perdido</option>
		<option value="2">Encontrado</option>
	</select>
	</p>

	<input type="submit" class="button" value="enviar"></p>

</form>
    public function nuevo(){
        $this->_view->titulo = 'nuevo';
        if($this->getInt('guardar') == 1){
            $this->_view->datos = $_POST;            

            if(!$this->getTexto('foto')){
                $this->_view->_error = 'introducir foto';
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
                         
            $this->_view->datos = false;
            $this->redireccionar('catalogo');
        }
        $this->_view->renderizar('nuevo', 'catalogo');
    }



