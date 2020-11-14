<?php

//se incluye datos de la conexion
include 'conexion2.php';
//read username from URL
$des = stripslashes(trim($descripcion));

function eliminar($cadena){
   return (preg_replace('[^ A-Za-z0-9_-ñÑ]', '', $cadena));}

function mostrarDatos ($resultados) {
if ($resultados !=NULL) {
echo "- Descriptor: ".$resultados['descripcion']."<br/> ";
                                                          }
$nueva=eliminar($des);

$pattern = '/oscar|Content-Type:|Bcc:|Cc:/1';
    if (preg_match($pattern, $nueva)) {
         die("inyection detectada");}

   $largo = strlen($descripcion);
    $letras = substr($descripcion,1,$largo-1);
    if(!ctype_alpha($letras))
      {
        $longitud=strlen($letras);
        if($longitud==0){echo "error ";}
        else{
   $i=1;
   $bandera=0;

      if($largo-1 ==1) {echo "ERROR 40098884";}
      else {

             while($i<$largo-1 and $bandera == 0){
             $letra=substr($nueva,$i,1) ;

      if(($letra>='a' and $letra<='z') or ($letra>='A' and $letra<='Z') or ($letra<=' ') and ($letra<>'&'))   { }
      else{$bandera=1;

         $i=$i+1;
      $1=substr($nueva,$largo-1,1) ;
                                      }
     if($1<>'"')   {echo"error 909";}
     else {
       $conexion = new mysqli($host_db, $users_db, $pass_db, $db_name);
       if($conexion->connect_error)
       {
         die ("La Conexion Fallo". $conexion->connect_error);
       }
       if($bandera==0){
           $buscarUsuario = "SELECT descripcion FROM productos WHERE  descripcion Like ". $nueva."'%'";
           $result = mysqli_query($conexion, $buscarUsuario);//guarda en una variable lo consultado
           $row=mysqli_fetch_array($result);//de la consulta los resultados obtenidos en fila
           if(!is_null($row)){
               $hayResultados = true;//Forzamos la entrada al bucle
               while($hayResultados==true){
                   if($row){//si hay coincidencias muestra como un array
                       mostrarDatos($row);
                   }else{
                       $hayResultados =false;//si no solo avisa que no con false
                   }
                   $row = mysqli_fetch_array($result);
               }
           }
       }
       mysqli_close($conexion);

     }
