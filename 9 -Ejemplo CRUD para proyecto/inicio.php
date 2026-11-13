<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>CRUD Biblioteca</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="Generator" content="Dev-PHP 2.6.1" />
	<meta name="Keywords" content="..." />
	<meta name="Description" content="." />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="estilos/estilos.css" />
</head>
<body>	
<div id="contenedor">
<div id="cuerpo">	
 <h1>BIBLIOTECA</h1>
 <nav>
    <ul>
    <li><a href="#">Gestionar</a>
      <ul>     
       <li><a href="inicio.php?opcion=1">Gestionar Autores</a></li>   
       <li><a href="inicio.php?opcion=2">Gestionar Libros</a></li>
       <li><a href="inicio.php?opcion=3">Asignar Autores a Libros</a></li>
     </ul>        
    </li>        
    <li><a href="">Busquedas</a>
	<ul>
	<li><a href="inicio.php?opcion=4">Listado de Libros/Autores</a></li>
	<li><a href="inicio.php?opcion=#">Agregar opciones...</a></li>
    </ul>
    </li>
  </ul>  
  </nav>
</div> <!--- cierra cuerpo  -->
<div id="vista" >

<?php
if(!empty($_GET['opcion']))
  $opcion=$_GET['opcion'];
else
  $opcion=0;  

require_once("conexion/conexion.php");
require_once("clases/Autor.php");
require_once("clases/Libro.php");
require_once("clases/Autor_Libro.php");

$con=new Conexion(); //crea un objeto de la conexion a la BD 
$conexion=$con->conectar(); //llama a la funcion conectar() que ejecuta la conexion, si encuentra la BD devuelve la información
//Nota:cada vez que se hace un clic en alguna opción del navegador la ejecucion termina(se pierden las variables)y comienza todo de nuevo

$autor=new Autor(); 
$libro=new Libro();
$autor_libro=new Autor_Libro();

switch($opcion){
 case 1:{ // Clase Autor
	    $consulta="SELECT * FROM autor order by apellido"; 
        $autor->Mostrar($conexion,$consulta);     
        echo"<br/><br/>";
        echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
        break;
	 }
case 11:{	 
	 $autor->Agregar($conexion); break;
  }
case 12:{	 
	 $autor->Modificar($conexion); break;
  }
case 13:{	 
	 $autor->Eliminar($conexion);  break;
  }  

 case 2:{ //Mostrar Libro
  $consulta="SELECT * FROM libro order by isbn"; 
  $libro->Mostrar($conexion,$consulta);     
  echo"<br/><br/>";
  echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
  break;  
  } 	
 case 21:{//Agregar Libro
	$libro->Agregar($conexion);	break; 	  
 }  
 case 22:{  //Modifica Libro
    $libro->Modificar($conexion);	break;	 	
    }	
 case 23:{//Elimina Libro
	$libro->Eliminar($conexion);  break;	
	} 
  
 case 3:{//Asigna Autor a Libro
	 $autor_libro->Agregar($conexion);  break; 	   	  
	  }  
 case 4:{//Listado de Libros y Autores
		$autor_libro->Mostrar($conexion);  
		echo '<a class="boton" href="inicio.php?opcion=0">Volver</a>';
		break; 
		}		
}// cierra switch

?>
</div> <!-- fin vista -->
<div id="cuerpo2">
</div>
</div> <!-- fin contenedor -->
</body>
</html>
