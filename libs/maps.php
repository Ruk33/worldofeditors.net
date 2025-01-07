<?php 

include "db.php";
include "map_info.php";
include "parse_color_tags.php";

function in_post($param_names)
{
    foreach ($param_names as $param_name) {
        if (!isset($_POST[$param_name])) {
            return false;
        }
    }

    return true;
}

function any_in_post($param_names)
{
    foreach ($param_names as $param_name) {
        if (isset($_POST[$param_name])) {
            return true;
        }
    }

    return false;
}

function post_value($param_name, $default_value = null)
{
    if (isset($_POST[$param_name])) {
        return $_POST[$param_name];
    }

    return $default_value;
}

$function = $_GET['funcion'];

if ($function == "listar") {
    header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

    $maps = find(
        "
        select distinct on (maps.map_file_name) *
        from maps
        where
            maps.name           ilike :term or
            maps.description    ilike :term or
            maps.author         ilike :term or
            maps.map_file_name  ilike :term
        order by maps.map_file_name, maps.created_at desc
        limit 50
        ",
        ["term" => "%" . $_GET["nombre"] . "%"]
    );

    echo json_encode($maps);
    exit();
}

$can_create = true;

if ($function != "crear") {
    $can_create = false;
}

if (!in_post(["name", "owner"])) {
    $can_create = false;
}

if (!any_in_post(["map_name", "uploaded_map"])) {
    $can_create = false;
}

if ($can_create) {
    $name  = post_value("name");
    $owner = post_value("owner");
    $map   = post_value("uploaded_map", post_value("map_name"));

    $bot_request = 
        "\nbot_map = " . $map . 
        "\nbot_owner = " . strtolower($owner) . 
        "\nbot_game = " . $name . "\n";
    file_put_contents("../pending/pending" . time(), $bot_request);

    discord_notification($map, $name, $name, $owner);

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

function discord_notification(string $map_file, string $name, string $game_name, string $user)
{
    $map = find_one(
        "
        select * from maps
        where maps.map_file_name = :map_file
        limit 1
        ",
        ["map_file" => $map_file]
    );

    $hook     = getenv("DISCORD_HOOK");
    $hook_key = getenv("DISCORD_HOOK_KEY");

    $url = "https://discord.com/api/webhooks/" . $hook . "/" . $hook_key;

    $hook_object = json_encode([
        "content" => "Partida creada <@&854822908874326026> !",
        "embeds" => [
            [
                "title" => strip_tags($name),
                "description" => strip_tags($descripcion),
                "url" => "https://worldofeditors.net/maps/" . str_replace(" ", "%20", $map_file),
                "color" => 1422025,
                "author" => [
                    "name" => strip_tags($game_name)
                ],
                "image" => [
                    "url" => "https://worldofeditors.net/storage/" . $map["thumbnail_path"]
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

