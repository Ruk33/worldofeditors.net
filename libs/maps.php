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

$funcion=$_GET['funcion'];

if($funcion=="similar"){
    header("Cache-Control: public, max-age=5, stale-while-revalidate=60");
    
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
}

if ($funcion == "listar") {
    header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

    // Sanitize inputs early
    $nombre = isset($_GET["nombre"]) ? preg_quote($_GET["nombre"], '/') : '';
    $tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : '';
    $orden = isset($_GET["orden"]) ? $_GET["orden"] : '';
    $limit = 50;

    // Use glob with GLOB_NOSORT for better performance
    $maps = glob("../maps/*.w3x", GLOB_NOSORT);
    if (!$maps) {
        echo json_encode([]);
        exit;
    }

    $results = [];
    $count = 0;

    foreach ($maps as $map_name) {
        if ($count >= $limit) {
            break;
        }

        $map_info = get_map_info(basename($map_name));
        if (!$map_info) {
            continue; // Skip invalid maps
        }

        // Skip if the map does not match the search query
        if (
            $nombre &&
            !preg_match("/{$nombre}/i", $map_info["name"] ?? '') &&
            !preg_match("/{$nombre}/i", $map_info["description"] ?? '') &&
            !preg_match("/{$nombre}/i", $map_info["author"] ?? '') &&
            !preg_match("/{$nombre}/i", $map_info["players_recommended"] ?? '') &&
            !preg_match("/{$nombre}/i", basename($map_name))
        ) {
            continue;
        }

        // Filter by type
        $is_melee = $map_info["is_melee"] ?? false;
        if (($tipo == "melee" && !$is_melee) || ($tipo == "custom" && $is_melee)) {
            continue;
        }

        // Prepare map data
        $map_file_size = filesize($map_name);
        $map_thumbnail = get_map_thumbnail(basename($map_name));

        $results[] = [
            "mapa" => basename($map_name),
            "peso" => $map_file_size,
            "nombre" => parse_color_tags($map_info["name"] ?? basename($map_name)),
            "jcj" => parse_color_tags($map_info["max_players"] ?? ''),
            "desc" => parse_color_tags($map_info["description"] ?? ''),
            "autor" => parse_color_tags($map_info["author"] ?? ''),
            "minimap" => $map_thumbnail ? "/PHP-MPQ/thumbnail.php?map=" . basename($map_name) : "minmap.png",
            "jp" => parse_color_tags($map_info["players_recommended"] ?? ''),
            "is_melee" => $is_melee,
        ];

        $count++;
    }

    echo json_encode($results);
}

if($funcion=="crear"){
    chdir("../");
    if(isset($_POST["mapname"])){
        $name = $_POST["name"];
        $map = $_POST["mapname"];
        $owner = $_POST["owner"];
        $file = fopen("pending/pending" . time(), "w");
        if ($file === false)
            die("can't create request." . var_dump(error_get_last()));
        if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . strtolower($owner) . "\nbot_game = " . $name . "\n") === false)
            die("can write request.");
        fclose($file);
        // echo "create pending<br>";
        $mapas=glob("processed/*");
        if(count($mapas)>10) unlink($mapas[0]);
        // echo "unlink processed<br>";
        //////////////////
        
        // webhookdisc($map,$name,$name,$owner,"");

        // echo "Discord enviado<br>";
        //////////////////
        Reg_Log("[SUCCEED][SELECT]",$name,$owner,$map,date('d/m/Y H:i:s')); 
        // echo "Log creado<br>";           
    }
    
    // Redirect to jugar.php
    {
        $nombre = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
        $owner = htmlspecialchars($_POST["owner"], ENT_QUOTES, 'UTF-8');

        // Build the query string safely
        $query_params = http_build_query([
            'success' => 'true',
            'name' => $nombre,
            'owner' => $owner
        ]);

        header("Location: /jugar.php?$query_params");
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