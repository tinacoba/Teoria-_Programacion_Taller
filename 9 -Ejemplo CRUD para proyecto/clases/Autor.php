<?php
header('Content-Type: text/html; charset=UTF-8');
class Autor{	
    private $cod_autor;
    private $nombre;
    private $apellido;
    private $pais;
    
   public function __construct($nom="",$ape="",$pais=""){	
       $this->nombre=$nom;
       $this->apellido=$ape;
       $this->pais=$pais;
	}       
    
    public function setNombre($nom){
		$this->nombre=$nom;
	}
    public function getNombre(){
		return $this->nombre;
	}
    public function setApellido($ape){
		$this->apellido=$ape;
	}
    public function getApellido(){
		return $this->apellido;
	}
    public function setPais($pais){
		$this->pais=$pais;
	}
    public function getPais(){
		return $this->pais;
	}
    
        
 public function Agregar($conexion){
		echo' 
	 <h3>AGREGAR NUEVO AUTOR </h3>
	 <form action="#" method="post">
	 <label for="nom">NOMBRE:</label>
	 <input type="text" name="nom"/><br/><br/>
	 <label for="ape">APELLIDO:</label>
	 <input type="text" name="ape"/><br/><br/>
	 <label for="pais">PAIS:</label>
	 <input type="text" name="pais"/><br/><br/>
	 <input type="submit" name="si" value="AGREGAR">
	 <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=1" formnovalidate >
	  </form>';
	  echo "<br/>";  
	  
	if(isset($_POST['si'])){  		
		$this->setNombre($_POST['nom']);
		$this->setApellido($_POST['ape']);
		$this->setPais($_POST['pais']);  
		
	   $consulta="INSERT INTO Autor(nombre,apellido,pais) values ('".$this->nombre."','".$this->apellido."','".$this->pais."')";
	   $resultado = $conexion->query($consulta);	
		if(!$resultado) echo "No se agregó el Autor, intente de nuevo.".$conexion->error;
        else{
         header("Location:inicio.php?opcion=1");
	   }
    }
  }  
    
public function Mostrar($conexion,$consulta){

  $resultado = $conexion->query($consulta); //query ejecuta el SQL
  if($resultado){ // si la ejecución del SQL fue exitosa
     echo "
     <h2>Listado de Autores</h2>
     <table border='1'>
     <tr>
     <th>Código</th>
     <th>Apellido</th>
     <th>Nombre</th>
     <th>Pais</th>
     </tr>";
     while($fila = $resultado->fetch_assoc()){ 
    //fetch_assoc() convierte el resultado del Select en array asociativo		 
       $cod_autor=$fila["cod_autor"];
	   $nombre=$fila["nombre"];
       $apellido=$fila["apellido"];
	   $pais=$fila["pais"];			
       echo "
       <tr>
       <td>".$cod_autor."</td>
       <td>".$apellido."</td>
       <td>".$nombre."</td>
       <td>".$pais."</td>
       </tr>";
       }
       echo'
	   <tr>
		<td colspan="4" style="padding:6px;">
		<a class="boton" href="inicio.php?opcion=11">Agregar Autor</a>
		<a class="boton" href="inicio.php?opcion=12">Modificar Autor</a>
		<a class="boton" href="inicio.php?opcion=13">Eliminar Autor</a>
		</td>
		</tr>';
		echo "</table></br>";		   
	    }
	   else echo "<h3>SIN DATOS</h3>"; 
 }
  
 public function Eliminar($conexion){
	 
  if(isset($_POST['si'])){
    $cod_autor=$_POST['cod_autor'];
    $consulta="select * from autor where cod_autor=".$cod_autor;
    $resultado=$conexion->query($consulta);		
    if($resultado){
	  $consulta1="DELETE FROM Autor where cod_autor=".$cod_autor;
	  $consulta2="DELETE FROM Autor_Libro where cod_autor=".$cod_autor;	
      $resultado1 = $conexion->query($consulta1);
	  $resultado2 = $conexion->query($consulta2);
	  if($resultado1 or $resultado2){			
	      header("Location:inicio.php?opcion=1");  exit();
	   }
	else { echo "<h3>No se pudo eliminar el autor</h3>";  }
   return;    
    }
  else{
     echo "<h3>No se encontró el Autor</h3>";
     echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';		   
     }  
  }
 
  echo "</br></br>";
//*********  Consulta de autores  ******************  
  $consulta="select autor.cod_autor,autor.nombre,autor.apellido,IFNULL(GROUP_CONCAT(autor_libro.cod_libro SEPARATOR '-'),'SinLibro') as Cod_Libro from autor left join autor_libro on autor.cod_autor=autor_libro.cod_autor GROUP BY autor.cod_autor";
  $resultado=$conexion->query($consulta);  
  if($resultado){
       echo "
       <h2>Autores y Codigos de Libros</h2>
       <table border='1' style='font-size:12px;'>
       <tr>
       <th>Código</th>
       <th>Nombre</th>
       <th>Apellido</th>
       <th>Cod_Libro</th>
       </tr>";         
       
       while($fila = $resultado->fetch_assoc()){
		echo "
		<tr>
		<td>".$fila["cod_autor"]."</td>
		<td>".$fila["nombre"]."</td>
		<td>".$fila["apellido"]."</td>
		<td>".$fila["Cod_Libro"]."</td>
		</tr>";
	    }
	    echo "</table><br/>";
	  }  
  echo'
  <form action="#" method="post">
  <h3>INGRESAR CODIGO DE AUTOR A ELIMINAR</h3>
  <label for="cod">CODIGO:</label>
  <input type="text" name="cod_autor"/>
  <input type="submit" name="si" value="ELIMINAR">
  <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=1" formnovalidate >
  </form>';    
  echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
}      

public function Modificar($conexion){	
//si todavia el usuario no seleccionó el botón ACEPTAR CAMBIOS del formulario, 
//la ejecución del programa continúa hasta mostrar un 
//formulario que pide el código del autor a modificar
 
  if(isset($_POST['si'])) { 
	$cod_autor=$_POST['cod_autor'];
	
	 $consulta="select * from autor where cod_autor=".$cod_autor;
    $resultado=$conexion->query($consulta);		
    if($resultado){
       $fila = $resultado->fetch_assoc(); 
	   echo' 
		 <h3>MODIFICAR DATOS DE AUTOR </h3>
		 <form action="#" method="post">
		 <label for="cod">CODIGO:</label>
		 <input type="text" name="cod_autor" value="'.$fila['cod_autor'].'" readonly ><br/><br/>
		 <label for="nom">NOMBRE:</label>
		 <input type="text" name="nombre" value="'.$fila['nombre'].'"><br/><br/>
		 <label for="ape">APELLIDO:</label>
		 <input type="text" name="apellido" value="'.$fila['apellido'].'"><br/><br/>
		 <label for="tel">PAIS:</label>
		 <input type="text" name="pais" value="'.$fila['pais'].'"><br/><br/>
		 <input type="submit" name="cambiar" value="ACEPTAR CAMBIOS">
		 <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=1" formnovalidate >
		</form></br>';	   
	}
  }	
else{
	
  if(isset($_POST['cambiar'])) {
	$cod_autor=$_POST['cod_autor'];  
	$nombre=$_POST['nombre'];
	$apellido=$_POST['apellido'];
	$pais=$_POST['pais'];
    $consulta="UPDATE autor SET nombre='".$nombre."',apellido='".$apellido."',pais='".$pais."' where cod_autor=".$cod_autor;
   // echo "$consulta </br>";
    $resultado = $conexion->query($consulta);
    if($resultado){
	   header("Location:inicio.php?opcion=1");//al modificar los datos en tabla Autor,se vuelve a la opcion 1 para mostrar la tabla
	   exit();
	 }
     else{
       echo "<h3>Autor no se modificó</h3>";
       echo $conexion->error;    
        }
     return;    
    }
   else{
	    
   echo "</br></br>";
   echo'
  <form action="#" method="post">
  <h3>INGRESAR CODIGO DE AUTOR A MODIFICAR</h3>
  <label for="cod">CODIGO:</label>
  <input type="text" name="cod_autor"/>
  <input type="submit" name="si" value="MODIFICAR">
  <input type="submit" name="no" value="CANCELAR" formaction="inicio.php?opcion=1" formnovalidate >
  </form>';    
  echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
    }
}//cierra else
}//cierra function

}// fin clase Autor
?>
