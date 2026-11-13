<?php
header('Content-Type: text/html; charset=UTF-8');
class Libro{	
    private $cod_libro;
    private $titulo;
    private $isbn;
    private $editorial;
    private $cant_paginas;
    
   public function __construct($titulo="",$isbn="",$editorial="",$cant_paginas=""){	
       $this->titulo=$titulo;
       $this->isbn=$isbn;
       $this->editorial=$editorial;
       $this->cant_paginas=$cant_paginas;
	}       
    
    public function setTitulo($titulo){
		$this->titulo=$titulo;
	}
    public function getTitulo(){
		return $this->titulo;
	}
    public function setIsbn($isbn){
		$this->isbn=$isbn;
	}
    public function getIsbn(){
		return $this->isbn;
	}
	public function setEditorial($editorial){
		$this->editorial=$editorial;
	}
    public function getEditorial(){
		return $this->editorial;
	}	
    public function setCant_paginas($cant_paginas){
		$this->cant_paginas=$cant_paginas;
	}
    public function getCant_paginas(){
		return $this->cant_paginas;
	}
            
 public function Agregar($conexion){
    echo' 
	 <h3>AGREGAR NUEVO LIBRO</h3>
	 <form action="" method="post">
	 <label for="nom">TITULO:</label>
	 <input type="text" name="titulo"/><br/><br/>
	 <label for="ape">ISBN:</label>
	 <input type="text" name="isbn"/><br/><br/>
	 <label for="tel">EDITORIAL:</label>
	 <input type="text" name="editorial"/><br/><br/>
	 <label for="tel">CANT. PAGINAS:</label>
	 <input type="text" name="cant_paginas"/><br/><br/>	 
	 <input type="submit" name="si" value="AGREGAR">
	 <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=2" formnovalidate >
	</form></br>';
	 
	if(isset($_POST['si'])){  		
		$this->setTitulo($_POST['titulo']);
		$this->setIsbn($_POST['isbn']);
		$this->setEditorial($_POST['editorial']);
		$this->setCant_paginas($_POST['cant_paginas']); 
	   
	   $consulta="INSERT INTO Libro(titulo,isbn,editorial,cant_paginas) values ('".$this->titulo."','".$this->isbn."','".$this->editorial."','".$this->cant_paginas."')";
	   $resultado = $conexion->query($consulta);	
		if(!$resultado) echo "No se agregó el Libro, intente de nuevo.".$conexion->error;
        else{
           header("Location:inicio.php?opcion=2");
           exit();
	     }
    }
 }   
 public function Mostrar($conexion,$consulta){
    $resultado = $conexion->query($consulta);
    if($resultado){
       echo "<h2>Listado de Libros</h2>
       <table border='1'>
       <tr>
       <th>Código</th>
       <th>Título</th>
       <th>ISBN</th>
       <th>Editorial</th>
       <th>Cant_páginas</th>
       </tr>";

      while($fila = $resultado->fetch_assoc()){
		$cod_libro=$fila["cod_libro"];
		$titulo=$fila["titulo"];
		$isbn=$fila["isbn"];
		$editorial=$fila["editorial"];
		$cant_paginas=$fila["cant_paginas"];
			
        echo "<tr>
        <td>".$cod_libro."</td>
        <td>".$titulo."</td>
        <td>".$isbn."</td>
        <td>".$editorial."</td>
        <td>".$cant_paginas."</td>
        </tr>";
     }
    echo'
	   <tr>
		<td colspan="5" style="padding:6px;">
		<a class="boton" href="inicio.php?opcion=21">Agregar Libro</a>
		<a class="boton" href="inicio.php?opcion=22">Modificar Libro</a>
		<a class="boton" href="inicio.php?opcion=23">Eliminar Libro</a>
		</td>
		</tr>
		</table><br/>
		';
	}
	else echo "<h3>SIN DATOS</h3>"; 
 }
  
 public function Eliminar($conexion){
  if(isset($_POST['si'])){
    $cod_libro=$_POST['cod_libro'];
    $consulta="select * from libro where cod_libro=".$cod_libro;
    $resultado=$conexion->query($consulta);		
    if($resultado){
	  $consulta1="DELETE FROM Libro where cod_libro=".$cod_libro;
	  $consulta2="DELETE FROM Autor_Libro where cod_libro=".$cod_libro;	
      $resultado1 = $conexion->query($consulta1);
	  $resultado2 = $conexion->query($consulta2);
	  if($resultado1 or $resultado2){			
	      header("Location:inicio.php?opcion=1");  exit();
	   }
	else { echo "<h3>No se pudo eliminar</h3>";  }
   return;    
    }
  else{
     echo "<h3>No se encontró el Libro</h3>";
     echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';		   
     }  
  }
 
  echo "</br></br>";
//*********  Consulta de libros  ******************  
  $consulta="select libro.cod_libro,libro.titulo,libro.isbn,IFNULL(GROUP_CONCAT(autor_libro.cod_autor SEPARATOR '-'),'SinAutor')as Cod_Autor from libro left join autor_libro on libro.cod_libro=autor_libro.cod_libro GROUP BY libro.cod_libro";
  $resultado=$conexion->query($consulta);  
  if($resultado){
       echo "<h2>Libros y Codigos de Autores</h2>
       <table border='1' style='font-size:12px;'>
       <tr>
       <th>Código</th>
       <th>Titulo</th>
       <th>Isbn</th>
       <th>Cod_Autor</th>
       </tr>";         
       while($fila = $resultado->fetch_assoc()){
		echo "<tr>
		<td>".$fila["cod_libro"]."</td>
		<td>".$fila["titulo"]."</td>
		<td>".$fila["isbn"]."</td>
		<td>".$fila["Cod_Autor"]."</td>
		</tr>";
	    }
	    echo "</table><br/>";
	  }  
  echo'
  <form action="#" method="post">
  <h3>INGRESAR CODIGO DE LIBRO A ELIMINAR</h3>
  <label for="cod">CODIGO:</label>
  <input type="text" name="cod_libro"/>
  <input type="submit" name="si" value="ELIMINAR">
  <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=1" formnovalidate >
  </form>';    
  echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>'; 
 }  
    
 public function Modificar($conexion){
	 
  if(isset($_POST['si'])) {  
	$cod_libro=$_POST['cod_libro'];
	 $consulta="select * from libro where cod_libro=".$cod_libro;
    $resultado=$conexion->query($consulta);		
    if($resultado){
       $fila = $resultado->fetch_assoc(); 
	   echo' 
	 <h3>MODIFICAR DATOS DE LIBRO </h3>
	 <form action="#" method="post">
	 <label for="cod">CODIGO:</label>
     <input type="text" name="cod_libro" value="'.$fila['cod_libro'].'" readonly /><br/><br/>
	 <label for="nom">TITULO:</label>
	 <input type="text" name="titulo" value="'.$fila['titulo'].'"/><br/><br/>
	 <label for="ape">ISBN:</label>
	 <input type="text" name="isbn" value="'.$fila['isbn'].'"/><br/><br/>
	 <label for="tel">EDITORIAL:</label>
	 <input type="text" name="editorial" value="'.$fila['editorial'].'"/><br/><br/>
	 <label for="tel">CANT. PAGINAS:</label>
	 <input type="text" name="cant_paginas" value="'.$fila['cant_paginas'].'"/><br/><br/>	 
	 <input type="submit" name="cambiar" value="ACEPTAR CAMBIOS">
	 <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=2" formnovalidate >
	 </form></br>';	   
	}
  }	
else{
	
  if(isset($_POST['cambiar'])) {
	$cod_libro=$_POST['cod_libro'];
		$titulo=$_POST['titulo'];
		$isbn=$_POST['isbn'];
		$editorial=$_POST['editorial'];
		$cant_paginas=$_POST['cant_paginas'];		
	 
	 $consulta="UPDATE libro SET titulo='".$titulo."',isbn='".$isbn."',editorial='".$editorial."',cant_paginas=".$cant_paginas." where cod_libro=".$cod_libro;
	 $resultado = $conexion->query($consulta);
    
    if($resultado){
	   header("Location:inicio.php?opcion=2");//si se modifican los datos en tabla Autor, se vuelve a la opcion mostrar para mostrar la tabla
	   exit();
	 }
     else{
       echo "<h3>Autor no se modificó</h3>";
       echo $conexion->error;    
        }
     return;    
  }
  echo "</br></br>";

   echo'
  <form action="#" method="post">
  <h3>INGRESAR CODIGO DE LIBRO A MODIFICAR</h3>
  <label for="cod">CODIGO:</label>
  <input type="text" name="cod_libro"/>
  <input type="submit" name="si" value="MODIFICAR">
  <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=2" formnovalidate >
  </form>';    
  echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
}
}
    
}// fin clase Libro
