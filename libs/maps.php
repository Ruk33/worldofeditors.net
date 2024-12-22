<?php 

include "map_info.php";
include "parse_color_tags.php";

$funcion = $_GET['funcion'];

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

        $results[] = [
            "mapa" => basename($map_name),
            "peso" => $map_file_size,
            "nombre" => parse_color_tags($map_info["name"] ?? basename($map_name)),
            "jcj" => parse_color_tags($map_info["max_players"] ?? ''),
            "desc" => parse_color_tags($map_info["description"] ?? ''),
            "autor" => parse_color_tags($map_info["author"] ?? ''),
            "minimap" => $map_info["thumbnail"],
            "jp" => parse_color_tags($map_info["players_recommended"] ?? ''),
            "is_melee" => $is_melee,
        ];

        $count++;
    }

    echo json_encode($results);
}

if ($funcion == "crear" && $_POST["name"] && $_POST["owner"] && $_POST["mapname"]) {
    $name  = $_POST["name"];
    $owner = $_POST["owner"];
    $map   = $_POST["mapname"];

    $bot_request = 
        "\nbot_map = " . $map . 
        "\nbot_owner = " . strtolower($owner) . 
        "\nbot_game = " . $name . "\n";
    file_put_contents("../pending/pending" . time(), $bot_request);

    webhookdisc($map, $name, $name, $owner, "");

    $safe_name  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safe_owner = htmlspecialchars($owner, ENT_QUOTES, 'UTF-8');

    // Build the query string safely
    $query_params = http_build_query([
        'success' => 'true',
        'name' => $safe_name,
        'owner' => $safe_owner
    ]);

    header("Location: /jugar.php?$query_params");
    exit();
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
}
