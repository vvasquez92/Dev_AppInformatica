<?php 
ob_start();
session_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

if($_SESSION['administrador']==1)
{
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Monitoreo Alertas</title>

    <!-- Bootstrap -->
    <link href="../public/build/css/bootstrap.min.css" rel="stylesheet">

    <!-- Personalizacion del Tema -->
    <link href="../public/build/css/custom.css" rel="stylesheet">
    
    <!-- Personalizacion del Tema -->
    <link href="../public/build/css/apigm.css" rel="stylesheet">
    
    <style>
            html, body{
                height:100%;   
            }
            #map_api{
                height:100%; 
                position: relative;
                margin: 0;
                padding: 0;  
            }
        </style>

  </head>

  <body>
        <!-- Cargamos mapa de API Google Maps -->
        <div id="map_api">
                      
        </div>
        
    <!-- Libreria API Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCl9tqKBSLLe1fW_AegQCGUI-DDUvbE448"></script>
    <!-- jQuery -->
    <script src="../public/build/js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../public/build/js/bootstrap.min.js"></script>
   <!-- Libreria de Personalizacion -->
    <script src="../public/build/js/custom_gma.js"></script>
   
    <!-- Custom Script Monitoreo -->
    <script type="text/javascript" src="scripts/monitoreo.js"></script>

  </body>
</html>

<?php 
}else{
  require 'nopermiso.php';
}
}
ob_end_flush();
 ?>