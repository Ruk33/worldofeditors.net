<?php

// 5 days
ini_set('session.gc_maxlifetime', 432000);
session_set_cookie_params(432000);
session_start();

include "../include/db.php";
include "../include/noindex.php";
include "../include/discord.php";

$id = $_POST["id"];

$map = find_one(
	"select * from maps where rowid = :id and uploaded_by = :uploaded_by limit 1;",
	[
		"id" => $id,
		"uploaded_by" => discord_get_user()->username,
	]
);

if ($map) {
	$storage_path = __DIR__ . "/storage/" . md5($map["map_file_name"]);

	// delete the map
	exec(sprintf("rm %s", escapeshellarg($map["map_path"])));
	// delete the storage stuff (thumbnail, etc)
	exec(sprintf("rm -rf %s", escapeshellarg($storage_path)));
	// delete the map from the db
	run_query("delete from maps where rowid = :id", ["id" => $id]);
}
