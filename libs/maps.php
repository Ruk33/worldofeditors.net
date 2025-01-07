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

