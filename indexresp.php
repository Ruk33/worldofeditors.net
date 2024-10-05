<?php
    $errorUploading = "";
    $createdSuccessfully = "";
    $owner = strtolower($_POST["owner"] or "");

    // Check if the form was submitted with a map to upload.
    if (isset($_POST["submit"]) && isset($_FILES["map"]) && $_FILES["map"]["name"]) {
        // Specify the directory where you want to store uploaded files
        $uploadDirectory = "maps/";

        // Get the uploaded file's name and temporary location
        $fileName = $_FILES["map"]["name"];
        $fileTmpName = $_FILES["map"]["tmp_name"];

        // Generate a unique name for the uploaded file to avoid overwriting existing files
        // $uniqueName = uniqid() . '_' . $fileName;
        $uniqueName = $fileName;

        // Set the final path where the file will be stored
        $uploadPath = $uploadDirectory . $uniqueName;

        // Check if the file was successfully uploaded
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // "File uploaded successfully. Stored as: " . $uniqueName;
            $map = $fileName;
            // $owner = $_POST["owner"];
            $name = $_POST["name"];

            $file = fopen("pending/pending" . time(), "w");
            if ($file === false)
                    die("can't create request." . var_dump(error_get_last()));
            if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . $owner . "\nbot_game = " . $name . "\n") === false)
                    die("can write request.");
            fclose($file);
            $createdSuccessfully =  " ".strtoupper($name)."<br><span class='subt'>CONTROL:</span> ".strtoupper($owner)." ";
        } else {
            $errorUploading = "Hubo un error al subir el mapa." . var_dump($_FILES);
        }
    // Check if the form was submitted with a map name.
    } else if (isset($_POST["submit"]) && isset($_POST["mapname"]) && $_POST["mapname"]) {
        $name = $_POST["name"];
        $map = $_POST["mapname"];
        $file = fopen("pending/pending" . time(), "w");
        if ($file === false)
            die("can't create request." . var_dump(error_get_last()));
        if (fwrite($file, "\nbot_map = " . $map . "\nbot_owner = " . $owner . "\nbot_game = " . $name . "\n") === false)
            die("can write request.");
        fclose($file);
        $createdSuccessfully = " ".strtoupper($name)."<br><span class='subt'>CONTROL:</span> ".strtoupper($owner)." ";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>WorldOfEditors</title>
    <link rel="shortcut icon" href="https://t0.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=https://worldofeditors.foroactivo.com&size=16" type="image/x-icon">
    <link rel="stylesheet" href="Estilosextra.css" />
    <script src="Scriptsextra.js"></script>
</head>
<body style="background-color: black;">
    <style>
        .container {
            border-image-source: url("https://ronenness.github.io/RPGUI/rpgui/img/border-image-golden.png");
            border-image-slice: 4 4 4 4;
            border-image-width: 4px;
            border-width: 4px;
            padding: 10px;
            border-style: solid;
            background-clip: padding-box;
            background-origin: padding-box;
            position: relative;
            box-sizing: border-box;
            border-image-repeat: repeat;
            background-color: #2a202a;
        }
        .field {
            margin-bottom: 10px;
        }
        input {
            background-color: white;
            border-radius: 4px;
            border: 0;
            padding: 4px;
            width: calc(100% - 8px);
            color: black;
        }
        button {
            background-color: rgb(170, 36, 9);
            padding: 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            border: 0;
            cursor: pointer;
        }
    </style>
    <?php if ($createdSuccessfully) { ?>
        <center class="mssg" > 
            <div class="marco">
                <h3>PARTIDA CREADA</h3>
                <p class="detalles"><span class="subt">NOMBRE: </span><?php echo $createdSuccessfully; ?></p>
                <p class="comandos wcsc">Puedes usar los siguientes comandos en la sala:<br>
                - <span>!start</span> = Inicia la partida.<br>
                - <span>!close {number}</span> = Cierra el numero de Slot especificado.<br>
                - <span>!comp {slot} {skill}</span> = Coloca una computadora en el Slot y asigna la dificultad. 0=Facil 1=Medio 2=Dificil.<br>
                - <span>mas detalles </span>= <a href="">Comandos de Ghost++</a></p>
                <a onclick="offmodal()" class="accept">LISTO</a>
            </div>
            <img src="./chain.png" alt="">
            <img src="./chain.png" alt="">
        </center>
    <?php } ?>
    <form action="#" method="post" enctype="multipart/form-data" class="container" style="color: white;">
        <h2 style="margin: 0; margin-bottom: 20px;;">CREAR PARTIDA</h2>
        <div class="field">
            <label for="map">Subir mapa</label>
            <input type="file" name="map" id="map" style="display: block;">
            <p><?php echo $errorUploading ?></p>

            <label for="mapname">O seleccionar mapa de la lista</label>
            <select class="wcsc" id="mapname" name="mapname" size="5" style="display: block;">
            <option value="">Selecciona un mapa.</option>
            <?php
            $files = scandir("maps");

            if ($files !== false) {
                foreach ($files as $file) {
                    if (preg_match('/\.(w3x|w3m)$/', $file)) {
                        echo "<option value='" . $file . "'>" . $file . "</option>";
                    }
                }
            }
            ?>
            </select>
        </div>
        <div class="field">
            <label for="owner">Usuario que crea la partida</label>
            <input type="text" name="owner" id="owner" value="<?php if(isset($_COOKIE["owner"])) echo $_COOKIE["owner"]; ?>" style="display: block;" required>
        </div>
        <div class="field">
            <label for="name">Nombre de la partida</label>
            <input type="text" maxlength="32" name="name" id="name" style="display: block;" required>
        </div>
        <button type="submit" value="Crear partida" name="submit">Crear partida</button>
    </form>
    <center class="mssg hispar" > 
        <div class="marco">
            <h3>ULTIMAS CREADAS</h3>
            <div class="partidas wcsc">
            <table>   
                <?php 
                //foreach (glob("*") as $nombre_fichero) {
                    foreach (array_reverse(glob("processed/*"), true) as $nombre_fichero) {
                    if (true || strpos($nombre_fichero, 'processed') !== false) {
                        //echo "<option value='1'> ". $nombre_fichero ." = ".filesize($nombre_fichero) . "</option>";
                        $lineas = file($nombre_fichero);?>
                        <tr>
                            <td><?=$lineas[2]?></td>
                            <td><?=$lineas[0]?></td>
                            <td><?=$lineas[1]?></td>
                            <td><?= date("F d Y H:i:s.", filectime($nombre_fichero))?></td>
                        </tr>
                    <?php }
                } ?>
            </table>
            </div>
            <a onclick="offmodal()" class="accept cerrar">LISTO</a>
            
        </div>
        <img src="img/chain.png" alt="">
        <img src="img/chain.png" alt="">
    </center>
    <a onclick="historial(this)" class="chat historial">
        <svg width="64px" height="49.5px" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.128"></g><g id="SVGRepo_iconCarrier"> <path d="M8.38101 17.267H56.381" stroke="#f4d800" stroke-width="6.4" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.38101 31.819H56.38" stroke="#f4d800" stroke-width="6.4" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.38101 46.067H56.38" stroke="#f4d800" stroke-width="6.4" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
        
    </a>
    <a onclick="chat(this)" class="chat"><img src="https://shikuso.000webhostapp.com/views/warcraft/WorldofEditors/discord.png" class="btn-disc"></a>
    <iframe src="https://discord.com/widget?id=721139249289887794&theme=dark" width="350" height="500" allowtransparency="true" 
        frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts" class="chatmarco chatfull"></iframe>
    <a  href="menu.php" class="backbtn">VOLVER</a>
</body>
</html>
