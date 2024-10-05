<?php
header('Access-Control-Allow-Origin: *');

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

require __DIR__ . "/vendor/autoload.php";

use TriggerHappy\MPQ\MPQArchive;
use TriggerHappy\MPQ\Stream\FileStream;

// $map = "(2)EchoIsles.w3x";
$map = $_GET["map"];
$map_path = "../maps/" . $map;
$map_info = "./map_info/" . $map . ".json";
$info_needs_to_be_generated = !file_exists($map_info);
// $info_needs_to_be_generated = true;

if ($info_needs_to_be_generated) {
        $mpq_debug = false;

        $mpq = new MPQArchive($map_path, $mpq_debug);

        $w3i = $mpq->readFile("war3map.w3i");

        // Ignore first three numbers, after that, we have the data we care about.
        $x = 3*4;

        $map_name = "";
        $author = "";
        $description = "";
        $players_recommended = "";

        while (($s = FileStream::byte($w3i, $x)) != 0)
                $map_name .= chr($s);

        while (($s = FileStream::byte($w3i, $x)) != 0)
                $author .= chr($s);

        while (($s = FileStream::byte($w3i, $x)) != 0)
                $description .= chr($s);

        while (($s = FileStream::byte($w3i, $x)) != 0)
                $players_recommended .= chr($s);

        $x += 4 * 8; // camera bounds;
        $x += 4 * 4; // camera bounds complemenets
        $x += 4; // map playable area w
        $x += 4; // map playable area h
        $x += 4; // map flags
        $x += 1; // main ground type
        $x += 4; // campaign background number

        // custom loading screen model
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // loading screen text
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // loading screen title
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // loading screen subtitle
        while (($s = FileStream::byte($w3i, $x)) != 0);

        $x += 4; // loading screen number

        // prologue screen path
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // prologue text
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // prologue title
        while (($s = FileStream::byte($w3i, $x)) != 0);

        // prologue subtitle
        while (($s = FileStream::byte($w3i, $x)) != 0);

        $x += 6 * 4; // uses terrain fog, fog start z h, fog end z h, fog density, fog r g b a, global weather

        // custom sound environment
        while (($s = FileStream::byte($w3i, $x)) != 0);

        $x += 5; // tileset id, water tinting r g b a

        // max number of players
        $max_players = FileStream::UInt32($w3i, $x);

        $map_name_escaped = escapeshellarg($map_name);
        $map_path_escaped = escapeshellarg($map_path);
        $command = "MPQExtractor -e war3map.wts -o ../storage $map_path_escaped 2>&1";
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);

        $map_name_string = "STRING " . intval(str_replace("TRIGSTR_", "", $map_name));
        $author_string = "STRING " . intval(str_replace("TRIGSTR_", "", $author));
        $description_string = "STRING " . intval(str_replace("TRIGSTR_", "", $description));
        $players_recommended_string = "STRING " . intval(str_replace("TRIGSTR_", "", $players_recommended));

        $wts = file_get_contents("../storage/war3map.wts");

        $map_name_value = strstr($wts, $map_name_string);
        $author_value = strstr($wts, $author_string);
        $description_value = strstr($wts, $description_string);
        $players_recommended_value = strstr($wts, $players_recommended_string);

        preg_match('/STRING \d+\R{\R(.*?)\R}/s', $map_name_value, $matches);
        $map_name = $matches[1];
        preg_match('/STRING \d+\R{\R(.*?)\R}/s', $author_value, $matches);
        $author = $matches[1];
        preg_match('/STRING \d+\R{\R(.*?)\R}/s', $description_value, $matches);
        $description = $matches[1];
        preg_match('/STRING \d+\R{\R(.*?)\R}/s', $players_recommended_value, $matches);
        $players_recommended = $matches[1];

        $result = array(
                "name" => $map_name,
                "author" => $author,
                "description" => $description,
                "players_recommended" => $players_recommended,
                "max_players" => $max_players,
        );

        $json_result = json_encode($result);
        file_put_contents($map_info, $json_result);
}

header("Content-Type: text/json");
readfile($map_info);
