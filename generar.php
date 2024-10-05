<?php   
    $CSVA=fopen("storage/mapas.csv","w"); 
    chdir("maps/");
    array_multisort(array_map("filemtime", ($Archivos = glob("*.{w3x,w3m}",GLOB_BRACE))), SORT_DESC, $Archivos);      
    foreach($Archivos as $mapa){
        if(substr($mapa, 0, 1)=="("){
            $jcj=substr(explode(")", $mapa)[0],1); 
            $nombre=substr($mapa, strlen(explode(")", $mapa)[0])+1) ;
        }else{
            $nombre=$mapa;
            $jcj=0;
        }
        $peso=filesize($mapa);
        $id=urlencode(openssl_encrypt($nombre,"AES-128-ECB", "woe"));
        $autor="Desconocido";
        $preview="minmap.png";
        $jp="X vs X";
        $desc="Aun no se le ha asignado una descripcion.";
        $tipo="custom";
        $linea= $mapa.";".trim(substr($nombre,0,-4)).";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;
        //echo $linea;
        fputs($CSVA, $linea.PHP_EOL);
    }
    fclose($CSVA);
?>