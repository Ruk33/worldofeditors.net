<?php 

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

include "../PHP-MPQ/get_map_info.php";
include "../PHP-MPQ/get_map_thumbnail.php";
include "parse_color_tags.php";

header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

$funcion=$_GET['funcion'];

if($funcion=="similar"){
    $nombre=preg_quote($_GET["nombre"]);
    $array = array();
    foreach (glob("../maps/*") as $mapa) {            
        if (preg_match("/{$nombre}/i", $mapa)) {
            $jsar=[];
            $jsar["nombre"]=substr($mapa, 8);
            $jsar["ruta"]=$mapa;
            $jsar["peso"]=filesize($mapa);
            array_push($array, $jsar);
        }            
    }        
    echo json_encode($array);
}if($funcion=="listar"){
    $nombre=preg_quote($_GET["nombre"]);
    $tipo=preg_quote($_GET["tipo"]);
    $orden=preg_quote($_GET["orden"]);

    $results = array();

    foreach (glob("../maps/*.w3x") as $map_name) {
        $map_file_size = filesize($map_name);

        $map_info = get_map_info(basename($map_name));
        
        $map_is_invalid = !$map_info;
        
        if ($map_is_invalid)
            continue;

        $matches_query = 
            $nombre == "" || 
            preg_match("/{$nombre}/i", $map_info["name"]) || 
            preg_match("/{$nombre}/i", $map_info["description"]) ||
            preg_match("/{$nombre}/i", $map_info["author"]) ||
            preg_match("/{$nombre}/i", $map_info["players_recommended"]) ||
            preg_match("/{$nombre}/i", basename($map_name));

        $does_not_matches_query = !$matches_query;

        if ($does_not_matches_query)
            continue;

        if ($tipo == "melee" && !$map_info["is_melee"])
            continue;

        if ($tipo == "custom" && $map_info["is_melee"])
            continue;
    
        $map_thumbnail = get_map_thumbnail(basename($map_name));
        
        $entry = array(
            "mapa" => basename($map_name),
            "peso" => $map_file_size,
            "nombre" => parse_color_tags($map_info["name"] ? $map_info["name"] : basename($map_name)),
            "jcj" => parse_color_tags($map_info["max_players"]),
            "desc" => parse_color_tags($map_info["description"]),
            "autor" => parse_color_tags($map_info["author"]),
            "minimap" => $map_thumbnail ? "/PHP-MPQ/thumbnail.php?map=" . basename($map_name) : "minmap.png",
            "jp" => parse_color_tags($map_info["players_recommended"]),
            "is_melee" => $map_info["is_melee"],
        );

        array_push($results, $entry);
    }

    echo json_encode($results);

    // $array = array();
    // chdir("../");
    // $file = fopen("storage/mapas.csv", 'r');        
    // while ((($mapa = fgetcsv($file, 1000, ';')) !== FALSE)) {
    //     $map_info = get_map_info($mapa[0]);

    //     if (!$map_info)
    //         continue;

    //     $mapa[1] = parse_color_tags($map_info["name"]);
    //     $mapa[2] = $map_info["max_players"];
    //     $mapa[6] = parse_color_tags($map_info["description"]);
    //     $mapa[4] = parse_color_tags($map_info["author"]);
    //     $mapa[5] = parse_color_tags($map_info["players_recommended"]);

    //     if($tipo=="ALL") {
    //         if($nombre==""){                      
    //             $jsar=[];                
    //             $jsar["mapa"]=$mapa[0];
    //             $jsar["peso"]=$mapa[3];
    //             $jsar["nombre"]=$mapa[1];
    //             $jsar["jcj"]=$mapa[2];    
    //             $jsar["desc"]=$mapa[6];    
    //             $jsar["autor"]=$mapa[4];
    //             $jsar["minimap"]=$mapa[8]; 
    //             $jsar["jp"]=$mapa[5];           
    //             array_push($array, $jsar);
    //         }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
    //             $jsar=[];                
    //             $jsar["mapa"]=$mapa[0];
    //             $jsar["peso"]=$mapa[3];
    //             $jsar["nombre"]=$mapa[1];
    //             $jsar["jcj"]=$mapa[2];  
    //             $jsar["desc"]=$mapa[6]; 
    //             $jsar["autor"]=$mapa[4];
    //             $jsar["minimap"]=$mapa[8];   
    //             $jsar["jp"]=$mapa[5];   
    //             array_push($array, $jsar);
    //         }
    //     }elseif($mapa[7]==$tipo){
    //         if($nombre==""){                      
    //             $jsar=[];                
    //             $jsar["mapa"]=$mapa[0];
    //             $jsar["peso"]=$mapa[3];
    //             $jsar["nombre"]=$mapa[1];
    //             $jsar["jcj"]=$mapa[2];    
    //             $jsar["desc"]=$mapa[6];    
    //             $jsar["autor"]=$mapa[4];
    //             $jsar["minimap"]=$mapa[8]; 
    //             $jsar["jp"]=$mapa[5];           
    //             array_push($array, $jsar);
    //         }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
    //             $jsar=[];                
    //             $jsar["mapa"]=$mapa[0];
    //             $jsar["peso"]=$mapa[3];
    //             $jsar["nombre"]=$mapa[1];
    //             $jsar["jcj"]=$mapa[2];  
    //             $jsar["desc"]=$mapa[6]; 
    //             $jsar["autor"]=$mapa[4];
    //             $jsar["minimap"]=$mapa[8];   
    //             $jsar["jp"]=$mapa[5];   
    //             array_push($array, $jsar);
    //         }
    //     }
    // }
    // fclose($file);
    // echo json_encode($array);
}if($funcion=="crear"){
    chdir("../");
    if(isset($_FILES["map"]) && $_FILES['map']['name'] != null){
        $file_name = $_FILES['map']['name'];
        $tmp_name = $_FILES['map']['tmp_name'];
        $Upload_Directory = "maps/";
        $UniqueName = $file_name;
        $Upload_Path = $Upload_Directory . $UniqueName;
        $valor=file_exists("maps/".$file_name);
        if (move_uploaded_file($tmp_name, $Upload_Path)) {
            $map = $file_name;
            $name = $_POST["name"];
            $owner = $_POST["owner"];    
            $file = fopen("pending/pending" . time(), "w");
            if ($file === false)
                die("0@can't create request." . var_dump(error_get_last()));
            if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . strtolower($owner) . "\nbot_game = " . $name . "\n") === false)
                die("can write request.");
            fclose($file);
            $mapas=glob("processed/*");
            if(count($mapas)>=12) unlink($mapas[0]);
            ///////////
            if(substr($map, 0, 1)=="("){
                $jcj=substr(explode(")", $map)[0],1); 
                $nombre=substr($map, strlen(explode(")", $map)[0])+1) ;
            }else{
                $nombre=$map;
                $jcj=0;
            }
            $peso=filesize("maps/".$map);
            $id=urlencode(openssl_encrypt($nombre,"AES-128-ECB", "woe"));
            
            $detalles = Agregarcsv($map,trim(substr($nombre,0,-4)),$jcj,$peso,"Desconocido","X vs X","Aun no se le ha asignado una descripcion.","custom","minmap.png",$id,$valor);
            webhookdisc($map,$detalles[1],$name,$owner,$detalles[6]);
            ///////////
            Reg_Log("[SUCCEED][UPLOAD]",$name,$owner,$map,date('d/m/Y H:i:s')); 
            echo "1@subida exitosa";
        } else {
            Reg_Log("[ERROR][UPLOAD]",$_POST["name"],$_POST["owner"],$file_name,date('d/m/Y H:i:s'));
            echo "0@Hubo un error al subir el mapa > ".var_dump($_FILES);
        }
    }else if(isset($_POST["mapname"])){
        $name = $_POST["name"];
        $map = $_POST["mapname"];
        $owner = $_POST["owner"];
        $file = fopen("pending/pending" . time(), "w");
        if ($file === false)
            die("can't create request." . var_dump(error_get_last()));
        if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . strtolower($owner) . "\nbot_game = " . $name . "\n") === false)
            die("can write request.");
        fclose($file);
        echo "create pending<br>";
        $mapas=glob("processed/*");
        if(count($mapas)>10) unlink($mapas[0]);
        echo "unlink processed<br>";
        //////////////////
        webhookdisc($map,$_POST["nombre"],$name,$owner,$_POST["description"]);
        echo "Discord enviado<br>";
        //////////////////
        Reg_Log("[SUCCEED][SELECT]",$name,$owner,$map,date('d/m/Y H:i:s')); 
        echo "Log creado<br>";           
    }        
}
function Reg_Log($estado,$partida,$user,$mapa,$fecha){
    $file = fopen("log.txt", "a");
    fwrite($file, "".$estado." => ".$fecha." [ ".$partida." > ".$user." > ".$mapa." ] " . PHP_EOL);
    fclose($file);
}
function Agregarcsv($mapa,$nombre,$jcj,$peso,$autor,$jp,$desc,$tipo,$preview,$id,$valor){
    $datos = [];
    $nueval ="";
    if (($gestor = fopen("storage/mapas.csv", 'r')) !== FALSE) {
        while (($fila = fgetcsv($gestor, 1000, ';')) !== FALSE) {
            if($valor== true && $fila[0]==$mapa){
                $nombre=$fila[1]; $jcj=$fila[2]; $peso=$fila[3]; $autor=$fila[4]; $jp=$fila[5]; $desc=$fila[6]; $tipo=$fila[7]; $preview=$fila[8]; $id=$fila[9];
            }else{
                $datos[] = $fila; 
            }                
        }
        fclose($gestor);
    }
    if (($gestor = fopen("storage/mapas.csv", 'w')) !== FALSE) {
        $nueval= "".$mapa.";".$nombre.";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;
        fputs($gestor, $nueval.PHP_EOL);
        foreach ($datos as $fila) {
            $linea= $fila[0].";".$fila[1].";".$fila[2].";".$fila[3].";".$fila[4].";".$fila[5].";".$fila[6].";".$fila[7].";".$fila[8].";".$fila[9];
            fputs($gestor, $linea.PHP_EOL);
        }
        fclose($gestor);
    }
    return ["".$mapa,"".$nombre,"".$jcj,"".$peso,"".$autor,"".$jp,"".$desc,"".$tipo,"".$preview,"".$id];
}
function webhookdisc(string $mapa,string $nombre,string $partida,string $user,string $descripcion){
    $url = "https://discord.com/api/webhooks/1278484082879103026/MaImrkWKRW5DwQESI_jmWn0MovwsSoLp9iXI-phGW-pWr1YSCGveLj41tNthJN7SvJGz";
    $hookObject = json_encode([
        "content" => "Partida creada <@&854822908874326026> !",
        "embeds"=> [
            [
                "title" => strip_tags($nombre),
                "description"=> strip_tags($descripcion),
                "url" => "https://worldofeditors.net/maps/".str_replace(" ","%20",$mapa),
                "color" => 1422025,
                "author" => [
                    "name" => strip_tags($partida)
                ],
                "image" => [
                    "url" => "https://worldofeditors.net/PHP-MPQ/thumbnail.php?map=".str_replace(" ","%20",$mapa)
                ]
            ]
        ],
        "username" =>"$user",
        "avatar_url" => "https://media.discordapp.net/attachments/479286377519775744/1277729272248926208/fba9d541e865b191d7e4c56907bfa311.webp?ex=66ce399d&is=66cce81d&hm=726aa6944415c10e99c8f7a0e2e922bf6af7415b7c2573d945cc5495f06c78ac&=&format=webp",
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init();
    curl_setopt_array( $ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]
    ]);
    $response = curl_exec( $ch );
    curl_close( $ch );
    
    $fp = fopen('results.json', 'w');
    fwrite($fp, $hookObject);
    fclose($fp);
    
}

?>