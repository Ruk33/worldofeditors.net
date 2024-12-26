<?php

include "libs/db.php";
include "libs/map_info.php";

$valid_request = 
    isset($_FILES["file_chunk"]) && 
    isset($_POST["file_name"]) && 
    isset($_POST["chunk"]) && 
    isset($_POST["total_chunks"]);

$invalid_request = !$valid_request;

if ($invalid_request) {
    http_response_code(400);
    exit();
}

// Directory where chunks will be saved
$target_dir     = __DIR__ . "/maps/";
$file_name      = $_POST["file_name"];
$chunk_index    = $_POST["chunk"];
$total_chunks   = $_POST["total_chunks"];

$chunk_tmp_name = $_FILES["file_chunk"]["tmp_name"];
$file_path      = $target_dir . $file_name;

// Check if only 1 chunk required, if so, just move the file.
if ($total_chunks == 1) {
    move_uploaded_file($chunk_tmp_name, $file_path);

    $info = get_map_info($file_name);

    insert("maps", [
        "name" => $info["name"],
        "author" => $info["author"],
        "description" => $info["description"],
        "is_melee" => $info["is_melee"] ? "true" : "false",
        "thumbnail_path" => $info["thumbnail"],
        "map_path" => $file_path,
        "map_file_name" => $file_name,
    ]);

    exit();
}

// Save the chunk to a temporary location
file_put_contents($file_path . ".part" . $chunk_index, file_get_contents($chunk_tmp_name));

// Check if all chunks are uploaded
if ($chunk_index + 1 == $total_chunks) {
    // Combine the chunks into the final file
    $final_file_data = "";
    for ($i = 0; $i < $total_chunks; $i++) {
        $chunk_data = file_get_contents($file_path . ".part" . $i);
        $final_file_data .= $chunk_data;
        // Remove the chunk after merging
        unlink($file_path . ".part" . $i);
    }

    // Write the final merged data
    file_put_contents($file_path, $final_file_data);

    $info = get_map_info($file_name);

    insert("maps", [
        "name" => $info["name"],
        "author" => $info["author"],
        "description" => $info["description"],
        "is_melee" => $info["is_melee"] ? "true" : "false",
        "thumbnail_path" => $info["thumbnail"],
        "map_path" => $file_path,
        "map_file_name" => $file_name,
    ]);
}
