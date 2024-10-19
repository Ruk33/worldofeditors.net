<?php

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

header("Cache-Control: public, max-age=604800, immutable");
header("Content-Type: image/png");

require __DIR__ . "/vendor/autoload.php";

use TriggerHappy\MPQ\MPQArchive;

$map = $_GET['map'];
$preview = "./thumbnails/" . $map . ".png";
$thumbnail_generated = file_exists($preview);
$thumbnail_needs_to_be_generated = !$thumbnail_generated;

if ($thumbnail_needs_to_be_generated) {
    $mpq_debug = false;
    $mpq = new MPQArchive("../maps/" . $map, $mpq_debug);

    $thumbnail = $mpq->readFile("war3mapPreview.tga") or $mpq->readFile("war3mapMap.tga");
    if ($thumbnail) {
        $tga_thumbnail = "../storage/" . $map . ".tga";
        file_put_contents($tga_thumbnail, $thumbnail);
        $tga_thumbnail_escaped = escapeshellarg($tga_thumbnail);
        $preview_escaped = escapeshellarg($preview);
        $command = "convert $tga_thumbnail -flip $preview_escaped";
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);
    } else {
        $thumbnail = $mpq->readFile("war3mapMap.blp");
        if ($thumbnail) {
            $blp_thumbnail = "../storage/" . $map . ".blp";
            file_put_contents($blp_thumbnail, $thumbnail);
            $blp_thumbnail_escaped = escapeshellarg($blp_thumbnail);
            $command = "BLPConverter -o ./thumbnails $blp_thumbnail_escaped 2>&1";
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);
        }
    }
}

readfile($preview);
