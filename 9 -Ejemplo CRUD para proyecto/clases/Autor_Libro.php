<?php
header('Content-Type: text/html; charset=UTF-8');
class Autor_Libro{	
    private $cod_autor;
    private $cod_libro;
        
   public function __construct($cod_autor="",$cod_libro=""){
	   $this->cod_autor=$cod_autor;
       $this->cod_libro=$cod_libro;
	}
    public function setCod_autor($cod_autor){
		$this->cod_autor=$cod_autor;
	}
    public function getCod_autor(){
		return $this->cod_autor;
	}
    public function setCod_libro($cod_libro){
		$this->cod_libro=$cod_libro;
	}
    public function getCod_libro(){
		return $this->cod_libro;
	}
	
 public function Agregar($conexion){
    $this->Mostrar($conexion);// se muestra el listado completo de libros y autores para mejor referencia
    
	if(isset($_POST['si'])){  		
		$this->setCod_autor($_POST['cod_autor']);
		$this->setCod_libro($_POST['cod_libro']);
		
		$consulta="select * from autor where cod_autor=".$this->cod_autor;
		$consulta1="select * from libro where cod_libro=".$this->cod_libro;		
		$result=$conexion->query($consulta);
		$result1=$conexion->query($consulta1);
		
	 if($result->num_rows >0 && $result1->num_rows >0){//si es mayor a 0 hy filas para mostrar
		$consulta2="INSERT INTO Autor_Libro(cod_autor,cod_libro) values ('".$this->cod_autor."','".$this->cod_libro."')";       
	    $resultado2 = $conexion->query($consulta2);	
		if(!$resultado2) echo "No se agregó el Autor_Libro, intente de nuevo.".$conexion->error;
        else{
          header("Location:inicio.php?opcion=4");          	
          exit();
          }	   
		return;				
	 }
	 else echo"<h3 style='color:red;'>Autor o Libro inexistente, intente de nuevo</h3>";
   } 
  echo' 
	 <h3>ASIGNAR AUTOR A LIBRO </h3>
	 <form action="#" method="post">
	 <label for="cod_libro">CODIGO LIBRO:</label>
	 <input type="text" name="cod_libro" /><br/><br/>
	 <label for="cod_autor">CODIGO AUTOR:</label>
	 <input type="text" name="cod_autor" /><br/>
	 <br/><br/>
	 <input type="submit" name="si" value="AGREGAR">
	 <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=0" formnovalidate >
	</form>';	   
	
	echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
  }
 
 public function Mostrar($conexion){        
	
     $consulta="SELECT * From libro inner join (autor_libro INNER JOIN autor ON autor_libro.cod_autor=autor.cod_autor) ON libro.cod_libro=autor_libro.cod_libro";
     $consulta1="SELECT * FROM libro where cod_libro NOT IN(select cod_libro from autor_libro)";
     $consulta2="SELECT * FROM autor where cod_autor NOT IN(select cod_autor from autor_libro)";
     $resultado = $conexion->query($consulta);
     $resultado1 = $conexion->query($consulta1);
     $resultado2 = $conexion->query($consulta2);
     if($resultado && $resultado1 && $resultado2){
       echo "<h2>Listado Libros/Autores</h2>
       <table border='1' style='font-size:12px;'>
       <tr>
       <th>Código</th>
       <th>Título</th>
       <th>ISBN</th>
       <th>Editorial</th>
       <th>Cant.Páginas</th>
       <th>Código Autor</th>
       <th>Nombre completo</th>
       </tr>";        
       while($fila = $resultado->fetch_assoc()){
		echo "<tr>
		<td>".$fila["cod_libro"]."</td>
		<td>".$fila["titulo"]."</td>
		<td>".$fila["isbn"]."</td>
		<td>".$fila["editorial"]."</td>
		<td>".$fila["cant_paginas"]."</td>
		<td>".$fila["cod_autor"]."</td>
		<td>".$fila["apellido"].",".$fila["nombre"]."</td>
		</tr>";
	    }
	    echo "</table><br/>";
	   echo"<h3>LIBROS SIN AUTOR ASIGNADO</h3>";
	   While($fila1 = $resultado1->fetch_assoc()){
	    echo "<p>Codigo:".$fila1["cod_libro"]."-Ttitulo:".$fila1["titulo"]."-Isbn:".$fila1["isbn"]."-Editorial:".$fila1["editorial"]."</p>";	    
	  	  }
	  	echo "</br>";
	   echo"<h3>AUTORES SIN LIBRO ASIGNADO</h3>";
	    While($fila2 = $resultado2->fetch_assoc()){
	    echo "<p>Codigo:".$fila2["cod_autor"]."-Nombre:".$fila2["nombre"]."-Apellido:".$fila2["apellido"]."</p>";	    
	  	  }
	  	echo "</br>";
   }  	  
  else echo "<h3>SIN DATOS</h3>"; 
  
    }
    
}

?>
