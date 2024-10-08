<?php   
    $nombre= "";
    $jcj="";
    $autor="";
    $jp="";
    $desc="";

    $CSVA=fopen("mapas.csv","w"); 
    chdir("maps/");
    array_multisort(array_map("filemtime", ($Archivos = glob("*.{w3x,w3m}",GLOB_BRACE))), SORT_DESC, $Archivos);      
    foreach($Archivos as $mapa){
        $people_json = file_get_contents('https://worldofeditors.net/PHP-MPQ/map_info.php?map='.str_replace(" ","%20",$mapa));
        $decoded_json = json_decode($people_json, false);
        if(json_last_error()===JSON_ERROR_NONE){
            $nombre= $decoded_json->name;
            $jcj=$decoded_json->max_players;
            $autor=$decoded_json->author;
            $jp=$decoded_json->players_recommended;
            $desc=str_replace("\r"," ",str_replace("\n",' ',$decoded_json->description));
        }else{
            if(substr($mapa, 0, 1)=="("){
                $jcj=substr(explode(")", $mapa)[0],1); 
                $nombre=substr($mapa, strlen(explode(")", $mapa)[0])+1) ;
            }else{
                $nombre=substr($mapa,0,-4);
                $jcj=0;
            }
            $autor="Desconocido";
            $jp="X vs X";
            $desc="Aun no se le ha asignado una descripcion.";
        }      
        $peso=filesize($mapa);
        $id=urlencode(openssl_encrypt($nombre,"AES-128-ECB", "woe"));
        $preview="minmap.png";
        $tipo="custom";
        $linea= $mapa.";".trim($nombre).";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;
        //echo $linea;
        fputs($CSVA, $linea.PHP_EOL);
    }
    fclose($CSVA);
?>