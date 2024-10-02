<?php

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

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
        $tga_thumbnail = $map . ".tga";
        file_put_contents($tga_thumbnail, $thumbnail);
        $tga_thumbnail_escaped = escapeshellarg($tga_thumbnail);
        $preview_escaped = escapeshellarg($preview);
        $command = "convert $tga_thumbnail $preview_escaped";
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);
    }else{
        $thumbnail = $mpq->readFile("war3mapMap.blp");
        if ($thumbnail) {
            $blp_thumbnail = $map . ".blp";
            file_put_contents($blp_thumbnail, $thumbnail);
            $blp_thumbnail_escaped = escapeshellarg($blp_thumbnail);
            $command = "../BLPConverter/build/bin/BLPConverter $blp_thumbnail_escaped";
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);
        }
    }
}

header("Content-Type: image/png");
readfile($preview);
