<?php 
    if(!isset($_COOKIE["animated"])) {        
        setcookie("animated", "1");
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="libs/main.css">
    <title>WorldOfEditors Online</title>
    <script src="libs/main.js"></script>
</head>
<style>
    
</style>
<body>
    <img src="./img/InicioBG.png" alt="Cargando..." class="load">
    <center id="ventanaModal" class="modal">
        <div class="modal-content">            
            <div id="alert-igual" >
                <h1>AVISO</h1>
                <p>Se detecto otros mapas con un nombre similar:</p><br>
                <ul id="similares"></ul><br>
                <p>Si eres el autor, solicita tu acceso al manager desde el discord de la comunidad a uno de los moderadores o si ya lo posees, accede al link que se te brindo para subir una nueva version del mapa.</p>
                <p>Puede que esto sea un error, si es asi, ignora el mensaje.</p>                
            </div>
            <div id="alert-upload">
                <h1>SUIENDO MAPA</h1>
                <div class="progressbar">
                    <div>
                        <h4>100%</h4>
                    </div>
                </div>
                <p>maps/mapa.wx3</p>
            </div>
            <div id="alert-creada" class="marco creada">
                <h1 style="margin-bottom: 0px;">PARTIDA CREADA</h1>
                <h1 style="margin-top: -45px;">( <span style="color: #1eaf5a;">!START </span>)</h1>
                <p class="detalles">
                    <span class="subt">PARTIDA: </span><span class="subname">NOMRETEST</span><br>
                    <span class="subt">MAPA: </span><span class="submapa">MAPATEST</span><br>
                    <span class='subt'>USUARIO: </span><span class="subcontrol">CONTROLTEST</span>
                </p>
                <p class="comandos wcsc">Puedes usar los siguientes comandos en la sala:<br>
                    - <span>!start</span> = Inicia la partida.<br>
                    - <span>!close {number}</span> = Cierra el numero de Slot especificado.<br>
                    - <span>!comp {slot} {skill}</span> = Coloca una computadora en el Slot y asigna la dificultad. 0=Facil 1=Medio 2=Dificil.<br>
                    - <span>mas detalles </span>= <a href="https://wiki.eurobattle.net/index.php/Ghost++:Commands" target="_blank">Comandos de Ghost++</a>
                </p>
            </div>
            <img src="./img/chain.png" alt="cadena" class="chains">
            <img src="./img/chain.png" alt="cadena" class="chains2">   
            <a class="btnaccept btnazullargo" id="cerrar">ACEPTAR</a>
        </div>
    </center>
    <center>
        <form class="container" id="Formulario">
            <div class="datos">
                <h1>CREAR PARTIDA</h1>
                <div class="partida">
                    <h4>NOMBRE DE LA PARTIDA</h4>
                    <input type="text" placeholder="" class="inputtext" maxlength="31" id="name" name="name"><br>
                    <span>Da un nombre a tu sala. Los nombre de las salas pueden tener 31 caracteres.</span>
                </div>
                <div class="usuario">
                    <h4>USUARIO</h4>
                    <input type="text" placeholder="" class="inputtext" id="owner" name="owner"><br>
                    <span>El nombre proporcionado debe ser el que usas en el servidor de warcraft III.</span>
                </div>
                <div class="busqueda">
                    <h4>SELECCIONE UN MAPA</h4>
                    <div class="avanzado">
                        <input id="buscar" type="text" placeholder="Buscar un mapa" >
                        <!-- TIPO -->
                        <input type="radio" id="r1" name="tipo" value="ALL" checked>
                        <label for="r1" class="btnazul lblbtn no-seleccionable" >All</label>
                        <input type="radio" id="r2" name="tipo" value="melee">
                        <label for="r2" class="btnazul lblbtn no-seleccionable" >melee</label>
                        <input type="radio" id="r3" name="tipo" value="custom">
                        <label for="r3" class="btnazul lblbtn no-seleccionable" >custom</label>
                        <!-- ORDEN -->
                        <input type="checkbox" id="orden">
                        <label for="orden" class="btnazul lblbtn no-seleccionable"> </label>
                        <!-- REFRESH -->
                        <a class="btnazul btnrefresh no-seleccionable" id="Refresh">
                            <svg fill="#f4d800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="97px" height="97px" viewBox="0 0 388.409 388.409" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M281.242,55.345c-9.18-65.484-132.804-33.66-168.3-14.076c-44.064,24.48-75.888,69.769-87.516,118.116 c-4.896,20.808-10.404,52.632,0.612,72.828c-11.016,0.611-20.808,3.06-23.868,9.18c-0.612,0.612,0,1.224,0,1.836l0,0 c-2.448,1.836-3.06,4.896-0.612,7.956c20.808,26.316,41.004,54.468,64.872,77.724c3.06,3.061,8.568,3.673,11.628,0 c20.196-23.256,29.988-53.243,44.676-79.56c2.448-0.612,4.284-1.224,6.12-2.448c3.672-2.448,3.672-7.956,0-10.403 c-7.956-4.896-17.748-3.061-26.316-3.061c-0.612,0-0.612,0-1.224,0c-9.18-33.66,5.508-72.216,24.48-100.368 c28.764-42.84,82.008-39.779,124.236-27.54c1.225,0.612,2.448,0,3.672,0c2.448,1.225,6.12-1.224,6.12-4.283 c0-3.061,3.061-7.345,4.284-9.792c2.448-4.896,6.12-9.181,9.18-13.464C278.794,71.869,287.362,63.301,281.242,55.345z"></path> <path d="M388.343,159.385c-0.612-3.672-2.448-6.12-4.896-7.956c-11.628-24.479-34.884-45.899-50.184-68.544 c-2.448-3.672-8.568-5.508-12.24-1.836c-22.032,23.256-42.229,47.736-63.036,72.216c-3.06,3.672,0,7.956,3.06,7.956l0,0 c10.404,6.732,26.929,5.508,41.005,4.284c3.672,44.063-14.076,82.008-52.633,106.487c-33.048,22.645-70.992,12.24-105.875,24.48 c-1.224,0-1.836,0.612-3.06,1.224c-1.224,0.612-1.836,0.612-3.06,1.225c-1.836,0.611-1.836,3.672-0.612,4.896 c-3.672,12.24-4.284,25.704-2.448,38.557c0,0,0,0,0,0.611l0,0c0,0.612,0,1.225,0,1.225c-1.836,1.836-2.448,4.896-0.612,6.731 c-0.612,4.284,3.672,7.344,7.344,7.956c1.224,0,1.836,0,3.06,0c47.124,30.601,115.056,3.672,155.448-28.152 c43.452-34.271,84.456-110.159,64.26-167.075c5.508,0.612,11.016,1.836,16.524,3.06 C385.282,169.177,388.954,163.669,388.343,159.385z"></path> </g> </g></svg>                
                        </a>
                    </div>
                </div>
                <div class="mapas">
                    <input type="file" name="map" id="map">
                    <label for="map" class="mapnamespan">
                        <img src="./img/path.png" alt=""><span for="map" id="mapnombre">Subir un mapa desde mi ordenador.</span>
                    </label>
                    <select name="mapname" id="mapname" size="5" class="wcsc" >
                        <?php $files = scandir("maps");
                        if ($files !== false) {
                            foreach ($files as $file) {
                                if (preg_match('/\.(w3x|w3m)$/', $file)) {
                                    if(substr($file, 0, 1)=="("){                                        
                                        echo "<option value='" . $file . "' class='mapalist nunicon ni".substr(explode(")", $file)[0],1)."'>" . substr($file, strlen(explode(")", $file)[0])+1) . "</option>"; 
                                    }else{
                                        echo "<option value='" . $file . "' class='mapalist'>" . $file . "</option>";
                                    }
                                }
                            }
                        } ?>
                    </select>
                    


                </div>
                
            </div>
            <div class="miniatura">
                <!-- <img src="img/minmap.png" alt=""> -->
                <img src="img/minmap.png" alt="minimapa" onerror="this.src='img/minmap.png';" />
                <button type="submit" <?php if(!isset($_COOKIE['cooldown'])) echo 'class="buttonAct"'; ?>>CREAR <label class="cooldown"  ></label></button>
            </div>
            <div class="detalles">
                <h3><img src="./img/nun/10.png" alt="">Desconocido</h3>
                <h5>Autor: <span id="autor">Desconocido</span></h5>
                <div>
                    <span>JUGADORES PROPUESTOS:</span>  
                    <span id="jp">0 vs 0</span>                  
                </div>
                <p>Aun no se le ha asignado una descripcion.</p>
            </div>
            
        </form>
    </center>
    <div class="animacion no-seleccionable">
        <input type="checkbox" name="anim" id="anim" <?php if(!isset($_COOKIE["animated"]) || $_COOKIE["animated"]==1) echo "checked"; ?> >
        <label for="anim">Animado</label>
    </div>
    <?php 
        if (isset($_COOKIE["animated"])) {
            if($_COOKIE["animated"]=="1") {
                echo '<video class="fondo" src="screene.mp4" autoplay muted disablePictureInPicture loop>';
            }else if($_COOKIE["animated"]=="0"){                
                echo '<img src="img/screen.jpg" alt="FONDO SIN ANIMACION" class="fondo">';                
            }  
        }
    ?>   
</body>
</html>