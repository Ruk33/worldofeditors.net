<?php

// List maps.

include "../include/db.php";
include "../include/noindex.php";

header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

$term = $_GET["nombre"];

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
    ["term" => "%" . $term . "%"]
);

echo json_encode($maps);
