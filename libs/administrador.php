<?php

include "../PHP-MPQ/get_map_info.php";

header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

$funcion=$_GET['funcion'];
if($funcion=="listar"){
    $nombre=preg_quote($_GET["nombre"]);
    $tipo=preg_quote($_GET["tipo"]);
    $orden=preg_quote($_GET["orden"]);

    $array = array();
    chdir("../");
    $file = fopen("storage/mapas.csv", 'r');        
    while ((($mapa = fgetcsv($file, 1000, ';')) !== FALSE)) {
        // $raw_map_info = file_get_contents("../PHP-MPQ/map_info/" . $mapa[0] . ".json");
        // $map_info = $raw_map_info ? json_decode($raw_map_info, true) : array(
        //     "name" => $mapa[1],
        //     "author" => $mapa[4],
        //     "description" => $mapa[6],
        //     "players_recommended" => $mapa[5],
        //     "max_players" => $mapa[2],
        // );
        $map_info = get_map_info($mapa[0]);
        if($tipo=="ALL") {
            if($nombre==""){                      
                $jsar=[];                
                $jsar["mapa"]=$mapa[0];
                $jsar["peso"]=$mapa[3];
                $jsar["nombre"]=$map_info["name"];
                $jsar["jcj"]=$map_info["max_players"];
                $jsar["desc"]=$map_info["description"];
                $jsar["autor"]=$map_info["author"];
                $jsar["minimap"]=$mapa[8];
                $jsar["jp"]=$map_info["players_recommended"];
                $jsar["id"]=$mapa[9]; 
                if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO"; 
                array_push($array, $jsar);
            }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
                $jsar=[];                
                $jsar["mapa"]=$mapa[0];
                $jsar["peso"]=$mapa[3];
                $jsar["nombre"]=$map_info["name"];
                $jsar["jcj"]=$map_info["max_players"];
                $jsar["desc"]=$map_info["description"];
                $jsar["autor"]=$map_info["author"];
                $jsar["minimap"]=$mapa[8];   
                $jsar["jp"]=$map_info["players_recommended"];
                $jsar["id"]=$mapa[9];
                if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO"; 
                array_push($array, $jsar);
            }
        }elseif($mapa[7]==$tipo){
            if($nombre==""){                      
                $jsar=[];                
                $jsar["mapa"]=$mapa[0];
                $jsar["peso"]=$mapa[3];
                $jsar["nombre"]=$map_info["name"];
                $jsar["jcj"]=$map_info["max_players"];
                $jsar["desc"]=$map_info["description"];
                $jsar["autor"]=$map_info["author"];
                $jsar["minimap"]=$mapa[8];
                $jsar["jp"]=$map_info["players_recommended"];
                $jsar["id"]=$mapa[9];
                if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO";           
                array_push($array, $jsar);
            }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
                $jsar=[];                
                $jsar["mapa"]=$mapa[0];
                $jsar["peso"]=$mapa[3];
                $jsar["nombre"]=$map_info["name"];
                $jsar["jcj"]=$map_info["max_players"];
                $jsar["desc"]=$map_info["description"];
                $jsar["autor"]=$map_info["author"];
                $jsar["minimap"]=$mapa[8];   
                $jsar["jp"]=$map_info["players_recommended"];
                $jsar["id"]=$mapa[9];
                if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO";  
                array_push($array, $jsar);
            }
        }
    }
    fclose($file);
    echo json_encode($array);
}else if($funcion=="borrar"){
    $mapa=preg_quote(urlencode($_GET["mapa"]));
    chdir("../");
    //echo $mapa;
    $datos = [];
    if (($gestor = fopen("storage/mapas.csv", 'r')) !== FALSE) {
        while (($fila = fgetcsv($gestor, 1000, ';')) !== FALSE) {
            if($fila[9]!=$mapa){                                       
                $datos[] = $fila; 
            } else echo $mapa;            
        }
        fclose($gestor);
    }
    if (($gestor = fopen("storage/mapas.csv", 'w')) !== FALSE) {
        foreach ($datos as $fila) {
            $linea= $fila[0].";".$fila[1].";".$fila[2].";".$fila[3].";".$fila[4].";".$fila[5].";".$fila[6].";".$fila[7].";".$fila[8].";".$fila[9];
            fputs($gestor, $linea.PHP_EOL);
        }
        fclose($gestor);
    }
}else if($funcion=="actualizar"){
    chdir("../");
    
    $mapa="";
    $nombre=$_POST['nombre']; 
    $jcj=$_POST['jcj']; 
    $peso="";
    $autor=$_POST['autor'];  
    $jp=$_POST['jp'];  
    $desc=str_replace("\r"," ",str_replace("\n",' ',$_POST['desc']));  
    $tipo=$_POST['tipo']; 
    $preview="minimap.png"; 
    $id=preg_quote(urlencode($_GET["mapa"])); 

    $datos = [];
    $nueval ="";
    if (($gestor = fopen("storage/mapas.csv", 'r')) !== FALSE) {
        while (($fila = fgetcsv($gestor, 1000, ';')) !== FALSE) {
            if($fila[9]==$id){  
                if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
                    $mapa=$_FILES["archivo"]['name'];
                    $peso=$_FILES["archivo"]['size'];
                    if (file_exists("maps/".$fila[0])){
                        unlink("maps/".$fila[0]);
                    }  
                    echo $nueval= "".$mapa.";".$nombre.";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;                      
                }else{
                    $mapa=$fila[0];
                    $peso=$fila[3];
                    $nfila=array();
                    $nfila[0]=$mapa;
                    $nfila[1]=$nombre;
                    $nfila[2]=$jcj;
                    $nfila[3]=$peso;
                    $nfila[4]=$autor;
                    $nfila[5]=$jp;
                    $nfila[6]=$desc;
                    $nfila[7]=$tipo;
                    $nfila[8]=$preview;
                    $nfila[9]=$id;
                    print_r( $nfila);
                    $datos[] = $nfila;
                }
            }else{
                $datos[] = $fila; 
            }                
        }
        fclose($gestor);
    }
    if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
        $file_name = $_FILES['archivo']['name'];
        $tmp_name = $_FILES['archivo']['tmp_name'];
        $UniqueName = $file_name;
        $Upload_Path = "maps/". $UniqueName;
        move_uploaded_file($tmp_name, $Upload_Path);
    }
            
    if (($gestor = fopen("storage/mapas.csv", 'w')) !== FALSE) {
        if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
            fputs($gestor, $nueval.PHP_EOL);
        }
        foreach ($datos as $fila) {
            $linea= $fila[0].";".$fila[1].";".$fila[2].";".$fila[3].";".$fila[4].";".$fila[5].";".$fila[6].";".$fila[7].";".$fila[8].";".$fila[9];
            fputs($gestor, $linea.PHP_EOL);
        }
        fclose($gestor);
    }
}
    
?>