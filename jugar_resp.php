<!DOCTYPE html>
<html>
<head>
    <title>WorldOfEditors Online</title>
    <link rel="shortcut icon" href="https://t0.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=https://worldofeditors.foroactivo.com&size=16" type="image/x-icon">
    <link rel="stylesheet" href="Estilosextra.css" />
    <script src="Scriptsextra.js"></script>
</head>
<body>
    <center class="mssg alert" > 
        <div class="marco creada">
            <h3 style="margin-bottom: 0px;">PARTIDA CREADA</h3>
            <h3 style="margin-top: 0px;">( <span style="color: #1eaf5a;">!START </span>)</h3>
            <p class="detalles">
                <span class="subt">NOMBRE: </span><span class="subname">NOMRETEST</span><br>
                <span class="subt">MAPA: </span><span class="submapa">MAPATEST</span><br>
                <span class='subt'>CONTROL: </span><span class="subcontrol">CONTROLTEST</span></p>
            <p class="comandos wcsc">Puedes usar los siguientes comandos en la sala:<br>
            - <span>!start</span> = Inicia la partida.<br>
            - <span>!close {number}</span> = Cierra el numero de Slot especificado.<br>
            - <span>!comp {slot} {skill}</span> = Coloca una computadora en el Slot y asigna la dificultad. 0=Facil 1=Medio 2=Dificil.<br>
            - <span>mas detalles </span>= <a href="https://wiki.eurobattle.net/index.php/Ghost++:Commands" target="_blank">Comandos de Ghost++</a></p>
            <a onclick="offmodal()" class="accept">LISTO</a>
        </div>
        <img src="img/chain.png" alt="">
        <img src="img/chain.png" alt="">
        <div class="marco subiendo">
            <h3>SUBIENDO MAPA</h3>
            <div class="progressbar">
                <div>
                    <h4>100%</h4>
                </div>
            </div>
            <p>maps/mapa.wx3</p>
        </div>        
    </center>
    <h5 class="version" title="10 Mayo 2024" style="cursor: pointer;">VERSION 0.8.9 </h5>
    <form action="#" method="post" enctype="multipart/form-data" class="container" style="color: white;">
        <h2 style="margin: 0; margin-bottom: 20px;;">CREAR PARTIDA</h2>
        <div class="field divmapa">
            <label for="map">DESDE ARCHIVO</label>
            <input type="file" name="map" id="map" style="display: block;">
            <label for="mapname">DESDE EL SERVIDOR</label>
            <div class="buscador">
                <input type="text" placeholder="BUSCAR MAPA" id="buscar" class="inputb">
                <a onclick="cargarmapas()">
                    <svg fill="#f4d800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="97px" height="97px" viewBox="0 0 388.409 388.409" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M281.242,55.345c-9.18-65.484-132.804-33.66-168.3-14.076c-44.064,24.48-75.888,69.769-87.516,118.116 c-4.896,20.808-10.404,52.632,0.612,72.828c-11.016,0.611-20.808,3.06-23.868,9.18c-0.612,0.612,0,1.224,0,1.836l0,0 c-2.448,1.836-3.06,4.896-0.612,7.956c20.808,26.316,41.004,54.468,64.872,77.724c3.06,3.061,8.568,3.673,11.628,0 c20.196-23.256,29.988-53.243,44.676-79.56c2.448-0.612,4.284-1.224,6.12-2.448c3.672-2.448,3.672-7.956,0-10.403 c-7.956-4.896-17.748-3.061-26.316-3.061c-0.612,0-0.612,0-1.224,0c-9.18-33.66,5.508-72.216,24.48-100.368 c28.764-42.84,82.008-39.779,124.236-27.54c1.225,0.612,2.448,0,3.672,0c2.448,1.225,6.12-1.224,6.12-4.283 c0-3.061,3.061-7.345,4.284-9.792c2.448-4.896,6.12-9.181,9.18-13.464C278.794,71.869,287.362,63.301,281.242,55.345z"></path> <path d="M388.343,159.385c-0.612-3.672-2.448-6.12-4.896-7.956c-11.628-24.479-34.884-45.899-50.184-68.544 c-2.448-3.672-8.568-5.508-12.24-1.836c-22.032,23.256-42.229,47.736-63.036,72.216c-3.06,3.672,0,7.956,3.06,7.956l0,0 c10.404,6.732,26.929,5.508,41.005,4.284c3.672,44.063-14.076,82.008-52.633,106.487c-33.048,22.645-70.992,12.24-105.875,24.48 c-1.224,0-1.836,0.612-3.06,1.224c-1.224,0.612-1.836,0.612-3.06,1.225c-1.836,0.611-1.836,3.672-0.612,4.896 c-3.672,12.24-4.284,25.704-2.448,38.557c0,0,0,0,0,0.611l0,0c0,0.612,0,1.225,0,1.225c-1.836,1.836-2.448,4.896-0.612,6.731 c-0.612,4.284,3.672,7.344,7.344,7.956c1.224,0,1.836,0,3.06,0c47.124,30.601,115.056,3.672,155.448-28.152 c43.452-34.271,84.456-110.159,64.26-167.075c5.508,0.612,11.016,1.836,16.524,3.06 C385.282,169.177,388.954,163.669,388.343,159.385z"></path> </g> </g></svg>
                </a>
            </div>
            <select class="wcsc" id="mapname" name="mapname" size="5" style="display: block;">
                <?php $files = scandir("maps");
                if ($files !== false) {
                    foreach ($files as $file) {
                        if (preg_match('/\.(w3x|w3m)$/', $file)) {
                            echo "<option value='" . $file . "'>" . $file . "</option>";
                        }
                    }
                } ?>
            </select>
        </div>
        <div class="field divowner">
            <label for="owner">Usuario que crea la partida*</label>
            <input class="inputb" type="text" name="owner" id="owner" value="<?php if(isset($_COOKIE["owner"])) echo $_COOKIE["owner"]; ?>" required>
        </div>
        <div class="field divname">
            <label for="name">Nombre de la partida*</label>
            <input class="inputb" type="text" maxlength="100" name="name" id="name" value="<?php if(isset($_COOKIE["owner"])) echo "Partida de ".$_COOKIE["owner"]; ?>" required>
        </div>
        <button type="submit" value="Crear partida" name="submit" >Crear partida <label class="cooldown" ></label></button>
        <div id="resultado" class="DivRes">
            <h4 class="desname"><?php if(isset($_COOKIE["owner"])) echo "Partida de ".$_COOKIE["owner"]; ?></h4>
            <span class="til">OWNER:</span><span class="des desowner">&nbsp;<?php if(isset($_COOKIE["owner"])) echo $_COOKIE["owner"]; ?></span><br>
            <span class="til">MAPA:</span><span class="des desmapa">&nbsp;seleccionar un mapa</span>
        </div>
    </form>
    <center class="mssg hispar" > 
        <div class="marco">
            <h3>ULTIMAS CREADAS</h3>
            <div class="partidas wcsc">
            <table>   
                <?php 
                    foreach (array_reverse(glob("processed/*"), true) as $nombre_fichero) {
                    if (true || strpos($nombre_fichero, 'processed') !== false) {
                        $lineas = file($nombre_fichero);?>
                        <tr>
                            <td><?=explode("=",$lineas[3])[1]?></td>
                            <td><?=explode("=",$lineas[2])[1]?></td>
                            <td><?=explode("=",$lineas[1])[1]?></td>
                            <td class="fecha-utc-a-local"><?= date("F d Y H:i:s", filectime($nombre_fichero))?></td>
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
    <a onclick="chat(this)" class="chat"><img src="img/discord.png" class="btn-disc"></a>
    <iframe src="https://discord.com/widget?id=721139249289887794&theme=dark" width="350" height="500" allowtransparency="true" 
        frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts" class="chatmarco chatfull"></iframe>
    <a  href="index.php" class="backbtn">VOLVER</a>

    <script>
    const fechas = document.getElementsByClassName("fecha-utc-a-local");
    for (const fecha of fechas) fecha.textContent = new Date(`${fecha.textContent} UTC`).toLocaleString()
    </script>
</body>
</html>
