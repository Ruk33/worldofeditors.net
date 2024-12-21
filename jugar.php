<?php 

$page_title = "Jugar";

ob_start();

?>

<script src="//unpkg.com/alpinejs" defer></script>

<style>
    form {
        display: grid;
        grid-template-columns: 700px 300px;
        gap: 20px;
    }

    input, select {
        background-color: black;
        border: 1px solid gray;
        padding: 10px;
        outline: none;
        display: block;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        /* 5 left padding + 5 padding right + borders */
        width: calc(100% - 22px);
        font-family: friz;
    }

    select {
        width: 100%;
    }

    option {
        padding: 10px;
        border-radius: 2px;
    }

    label {
        display: block;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    button {
        display: block;
        background-image: url("./img/btn.png");
        background-repeat: round;
        border-radius: 5px;
        color: gold;
        text-decoration: none;
        padding: 10px;
        padding-left: 50px;
        padding-right: 50px;
        margin-top: 15px;
        margin-bottom: 15px;
        border: 1px solid #361515;
        box-shadow: 0 0 5px black;
        transition: all 300ms;
        font-size: 16px;
        font-family: friz;
        text-transform: uppercase;
        position: absolute;
        bottom: -30px;
        left: 75px;
        cursor: pointer;
    }

    button:hover {
        color: white;
    }

    button:disabled {
        filter: grayscale(100%);
    }

    form img {
        border: 1px solid gray; 
        border-radius: 5px;
        box-shadow: 0px 0px 0px 1px black;
    }
</style>

<h1>Jugar</h1>

<?php
    $game_created = isset($_GET['success']);
    $game_created_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '???';
    $game_created_owner = isset($_GET['owner']) ? htmlspecialchars($_GET['owner']) : 'Unknown';
?>

<?php if ($game_created) { ?>
    <div style="margin-bottom: 50px;"> 
        <h2>Partida creada!</h2>
        <p>La partida <?php echo $game_created_name ?> ya ha sido creada. El usuario <?php echo $game_created_owner ?> puede iniciar el juego con el comando <code>!start</code>.</code></p>
    </div>
<?php } ?>

<form
    enctype="multipart/form-data"
    method="post"
    action="./libs/maps.php?funcion=crear"
    x-data="{ 
        maps: [], 
        selected_map: { nombre: '', autor: '', desc: '', mapa: '', }, 
        map_preview: '',
        map_term: '',

        form: {
            name: '',
            owner: '',
            map: '',
            mapname: '',
        },
    }"
    x-effect="
        // Load maps when the page loads.
        maps = await (await fetch('/libs/maps.php?funcion=listar&nombre=' + encodeURIComponent(map_term) + '&tipo=ALL&orden=false')).json();
    "
>
    <div>
        <label>
            Nombre de la partida
            <input id="name" name="name" x-model="form.name" placeholder="Nombre de la partida" />
        </label>

        <label>
            Usuario
            <input id="owner" name="owner" x-model="form.owner" placeholder="El nombre del usuario que esta creando la partida" />
        </label>

        <label>
            Subi un mapa
            <input type="file" name="map" id="map" x-model="form.map" />
        </label>
        
        <label>
            O, busca uno de nuestros mapas alojados:
            <input x-model.debounce="map_term" placeholder="Islas eco..." />
        </label>
        <select 
            name="mapname" 
            id="mapname" 
            size="10" 
            x-model="form.mapname"
            x-on:change="selected_map = maps.find(map => map.mapa === form.mapname)"
        >
            <template x-for="map in maps">
                <option x-bind:value="map.mapa" x-html="map.nombre"></option>
            </template>
        </select>
    </div>
    
    <div style="text-align: center;">
        <div style="position: relative; margin-bottom: 50px;">
            <img 
                width="302px" 
                src="./img/minmap.png" 
                alt="Vista previa del mapa seleccionado"
                x-bind:src="map_preview" 
                x-effect="
                    // Set map preview when selected map gets updated
                    map_preview = selected_map.mapa ? './PHP-MPQ/thumbnail.php?map=' + selected_map.mapa : './img/minmap.png';
                "
            >
            <button 
                :disabled="!(form.name && form.owner && (form.map || form.mapname))"
                type="submit"
            >
                Crear
            </button>
        </div>

        <h2 style="font-weight: normal;" x-html="selected_map.nombre"></h2>
        <h3 style="font-weight: normal;" x-html="selected_map.autor"></h3>

        <p style="text-align: left; font-size: 16px;" x-html="selected_map.desc"></p>
    </div>
</form>
    
<?php
    $content = ob_get_clean();
    include "index.php";
?>
