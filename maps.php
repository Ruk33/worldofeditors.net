<?php

if(isset($_FILES["map"]) && $_FILES['map']['name'] != null){

    $file_name = $_FILES['map']['name'];
    $tmp_name = $_FILES['map']['tmp_name'];
    $Upload_Directory = "maps/";

    $UniqueName = $file_name;
    $Upload_Path = $Upload_Directory . $UniqueName;

    if (move_uploaded_file($tmp_name, $Upload_Path)) {
        
        $map = $file_name;
        $name = $_POST["name"];
        $owner = $_POST["owner"];

        $file = fopen("pending/pending" . time(), "w");
        //$file = fopen("pending" . time(), "w");
        if ($file === false)
                die("can't create request." . var_dump(error_get_last()));
        if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . strtolower($owner) . "\nbot_game = " . $name . "\n") === false)
                die("can write request.");
        fclose($file);  
        $mapas=glob("processed/*");
        if(count($mapas)>10) unlink($mapas[0]); 
        Reg_Log("[SUCCEED][UPLOAD]",$name,$owner,$map,date('d/m/Y H:i:s'));         
    } else {
        Reg_Log("[ERROR][UPLOAD]",$_POST["name"],$_POST["owner"],$file_name,date('d/m/Y H:i:s'));
        $errorUploading = "Hubo un error al subir el mapa." . var_dump($_FILES);
    }

}else if(isset($_POST["mapname"])){

    $name = $_POST["name"];
    $map = $_POST["mapname"];
    $owner = $_POST["owner"];
    $file = fopen("pending/pending" . time(), "w");
    if ($file === false)
        die("can't create request." . var_dump(error_get_last()));
    if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . strtolower($owner) . "\nbot_game = " . $name . "\n") === false)
        die("can write request.");
    fclose($file);
    $mapas=glob("processed/*");
    if(count($mapas)>10) unlink($mapas[0]);
    Reg_Log("[SUCCEED][SELECT]",$name,$owner,$map,date('d/m/Y H:i:s'));
}

function Reg_Log($estado,$partida,$user,$mapa,$fecha){
    $file = fopen("log.txt", "a");
    fwrite($file, "".$estado." => ".$fecha." [ ".$partida." > ".$user." > ".$mapa." ] " . PHP_EOL);
    fclose($file);
}

?>
