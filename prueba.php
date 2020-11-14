[12:09, 14/11/2020] Goni: <?php
$url_actual = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
if($url_actual <> 'http://localhost/prueba/singet.php') {//aqui cambian la ruta segun su datos
    //
echo "hola mundo";
}
else{
    echo "hola perro";
}
?>
[12:40, 14/11/2020] Goni: <?php
$url_actual = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
if($url_actual == 'http://localhost/prueba/singet.php') {
echo "hola mundo";
}
else if($url_actual == 'http://localhost/prueba/singet.php?'){
    echo "no mames";
}
else if($url_actual == 'http://localhost/prueba/singet.php?descripcion%22'){
    echo"no mames de nuevo";
}
else{

    include 'p_conexion.php';
    //aqui unicamente aqui se recupera la variable en la url
    $descripcion = $_GET['descripcion'];
    //se mide el tamanio de la palabra que recibimos
    $des = stripslashes(trim($_GET['descripcion']));

    //funcion para eliminar
    function eliminar($cadena){
        return (preg_replace('[^ A-Za-z0-9?-ñ]','',$cadena));
    }
    //funcion para mostrar los datos
    function mostrarDatos($resultados){
        if($resultados != NULL){
            echo "<h1>- Descriptor: ".$resultados['descripcion']."<br/></h1>";
        }
    }
    $nueva=eliminar($des);
    //disuadir las inyecciones de encabezado más comunes
    $pattern = '/root|Content-Type:|Bcc:|Cc:/i';
    if(preg_match($pattern, $nueva)){
        die("inyection detectada");
    }
    //asignamos un iterador
    $i=1;
    //asignamos una varible bandera
    $bandera=0;
    $largo = strlen($descripcion);
    $letras = substr($descripcion,1,$largo-1);
    if(!ctype_alpha($letras)){
        $longitud=strlen($letras);
        if($longitud==0){echo"error";}
        else{
            //dentro del while vamos a limitar lo que se puede buscar en la consulta desde la url
            while($i<$largo-1 and $bandera == 0){//aqui con bandera evitamos que haya un desvorde datos con bandera,
                // pues en la bd no hay numeros, por lo tanto se detine al primer numero que reconozca
                //en esta parte se hara el avance letra por letra para verificar que cumpla con los requisitosdentro del if
                $letra = substr($descripcion,$i,1);
                if(($letra>='a' and $letra<='z') or ($letra>='A' and $letra<='Z') or ($letra<=' ')){}
                else{$bandera=1;}
                $i++;
            }
            //aqui se hace la conxion con la bd
            $conexion = new mysqli("127.0.0.1", "root","","crm");
            //verifica la conexion, si no esta conectada muestra un mensaje elegante
            if($conexion->connect_error){
                die("La conexion falló: ".$conexion->connect_error);
            }
            if($bandera==0){
            //guardamos la sentencia en una variable de acuerdo a la descripcion que es variable
                $buscarUsuario = "SELECT descripcion FROM productos WHERE  descripcion Like ". $descripcion."'%'";
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
    }
}
?>
