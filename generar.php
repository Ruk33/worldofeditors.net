<?php   
    $CSVA=fopen("storage/mapas.csv","w"); 
    chdir("maps/");
    array_multisort(array_map("filemtime", ($Archivos = glob("*.{w3x,w3m}",GLOB_BRACE))), SORT_DESC, $Archivos);      
    foreach($Archivos as $mapa){
        $people_json = file_get_contents('https://worldofeditors.net/PHP-MPQ/map_info.php?map='.str_replace(" ","%20",$mapa));
        $decoded_json = json_decode($people_json, false);
        $nombre= $decoded_json->name;
        $jcj=$decoded_json->max_players;

        $peso=filesize($mapa);
        $id=urlencode(openssl_encrypt($nombre,"AES-128-ECB", "woe"));
        $autor=$decoded_json->author;
        $preview="minmap.png";
        $jp=$decoded_json->players_recommended;
        $desc=str_replace("\r"," ",str_replace("\n",' ',$decoded_json->description));
        $tipo="custom";
        $linea= $mapa.";".trim($nombre).";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;
        //echo $linea;
        fputs($CSVA, $linea.PHP_EOL);
    }
    fclose($CSVA);
?>