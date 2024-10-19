<?php

require __DIR__ . "/vendor/autoload.php";

use TriggerHappy\MPQ\MPQArchive;

function get_map_thumbnail($map) {
    try {
        $preview = __DIR__ . "/thumbnails/" . $map . ".png";
        $thumbnail_generated = file_exists($preview);
        $thumbnail_needs_to_be_generated = !$thumbnail_generated;

        if ($thumbnail_needs_to_be_generated) {
            $mpq_debug = false;
            $mpq = new MPQArchive(__DIR__ . "/../maps/" . $map, $mpq_debug);

            $thumbnail = $mpq->readFile("war3mapPreview.tga") or $mpq->readFile("war3mapMap.tga");
            if ($thumbnail) {
                $tga_thumbnail = __DIR__ . "/../storage/" . $map . ".tga";
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
                    $blp_thumbnail = __DIR__ . "/../storage/" . $map . ".blp";
                    file_put_contents($blp_thumbnail, $thumbnail);
                    $thumbnails_folder_escaped = escapeshellarg(__DIR__ . "/thumbnails");
                    $blp_thumbnail_escaped = escapeshellarg($blp_thumbnail);
                    $command = "BLPConverter -o $thumbnails_folder_escaped $blp_thumbnail_escaped 2>&1";
                    $output = [];
                    $return_var = 0;
                    exec($command, $output, $return_var);
                }
            }
        }

        return $preview;
    } catch (Exception $e) {
        return false;
    }
}
