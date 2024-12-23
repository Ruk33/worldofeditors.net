<?php 

include "db.php";
include "map_info.php";
include "parse_color_tags.php";

$funcion = $_GET['funcion'];

if ($funcion == "listar") {
    header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

    $query = run_query(
        "
        select *
        from maps
        where 
            maps.name           ilike :term or
            maps.description    ilike :term or
            maps.author         ilike :term or
            maps.map_file_name  ilike :term
        order by maps.created_at desc
        limit 50
        ",
        ["term" => "%" . $_GET["nombre"] . "%"]
    );

    $maps = $query->fetchAll();

    echo json_encode($maps);
    exit();
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

function webhook_disc(string $mapa, string $nombre, string $partida, string $user, string $descripcion)
{
    $url = "https://discord.com/api/webhooks/1278484082879103026/MaImrkWKRW5DwQESI_jmWn0MovwsSoLp9iXI-phGW-pWr1YSCGveLj41tNthJN7SvJGz";

    $hook_object = json_encode([
        "content" => "Partida creada <@&854822908874326026> !",
        "embeds" => [
            [
                "title" => strip_tags($nombre),
                "description" => strip_tags($descripcion),
                "url" => "https://worldofeditors.net/maps/" . str_replace(" ", "%20", $mapa),
                "color" => 1422025,
                "author" => [
                    "name" => strip_tags($partida)
                ],
                "image" => [
                    "url" => "https://worldofeditors.net/PHP-MPQ/thumbnail.php?map=" . str_replace(" ", "%20", $mapa)
                ]
            ]
        ],
        "username" => $user,
        "avatar_url" => "https://media.discordapp.net/attachments/479286377519775744/1277729272248926208/fba9d541e865b191d7e4c56907bfa311.webp?ex=66ce399d&is=66cce81d&hm=726aa6944415c10e99c8f7a0e2e922bf6af7415b7c2573d945cc5495f06c78ac&=&format=webp",
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hook_object,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
}

