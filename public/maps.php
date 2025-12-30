<?php

session_start();

// List maps.

include "../include/db.php";
include "../include/noindex.php";

// we need discord so we can tell which maps the user can/can't delete.
include "../include/discord.php";

// not sure this is that helpful and it's annoying that i can't
// see the real map list with a delay of 5 seconds
// header("Cache-Control: public, max-age=5, stale-while-revalidate=60");

$term = $_GET["nombre"];

$maps = find(
    "
    select
        maps.rowid,
        maps.name,
        maps.author,
        maps.description,
        maps.is_melee,
        maps.thumbnail_path,
        maps.map_path,
        maps.map_file_name,
        maps.created_at,
        maps.max_players,
        CASE WHEN maps.uploaded_by = :uploaded_by THEN true ELSE false END AS can_delete
    from
        maps
    where
        maps.name != '' and
        (
            maps.name          like :term collate nocase or
            maps.description   like :term collate nocase or
            maps.author        like :term collate nocase or
            maps.map_file_name like :term collate nocase
        )
    group by
        maps.map_file_name
    order by
        maps.created_at desc
    limit 150;
    ",
    [
        "term" => "%" . $term . "%",
        "uploaded_by" => discord_get_user()->username,
    ]
);

echo json_encode($maps);
