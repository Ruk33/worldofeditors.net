<?php 

$page_title = "Jugar";

ob_start();

?>

<script src="//unpkg.com/alpinejs" defer></script>

<style>
    #jugar form {
        display: grid;
        grid-template-columns: 700px 300px;
        gap: 20px;
    }

    #jugar input, select {
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

    #jugar select {
        width: 100%;
    }

    #jugar option {
        padding: 10px;
        border-radius: 2px;
    }

    #jugar label {
        display: block;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    #jugar button {
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

    #jugar button:hover {
        color: white;
    }

    #jugar button:disabled {
        filter: grayscale(100%);
    }

    #jugar form img {
        border: 1px solid gray; 
        border-radius: 5px;
        box-shadow: 0px 0px 0px 1px black;
    }
</style>

<div id="jugar">

<h1>Jugar</h1>

<?php
    $game_created = isset($_GET['success']);
    $game_created_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '???';
    $game_created_owner = isset($_GET['owner']) ? htmlspecialchars($_GET['owner']) : 'Unknown';
?>

<?php if ($game_created) { ?>
    <div style="margin-bottom: 50px;"> 
        <h2 id="partida_creada">Partida creada!</h2>
        <p id="partida_creada_detalles">La partida <?php echo $game_created_name ?> ya ha sido creada. El usuario <?php echo $game_created_owner ?> puede iniciar el juego con el comando <code>!start</code>.</code></p>
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
        is_uploading_map: false,
        uploading_progress: 0,

        form: {
            name: '',
            owner: '',
            mapname: '',
        },
    }"
    x-effect="
        // Load maps when the page loads and/or the term changes.
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
            <input
                type="file"
                :disabled="is_uploading_map"
                x-on:change="
                const files = event.target.files || [];
                const file = files[0];
                if (!file)
                    return;

                is_uploading_map = true;
                uploading_progress = 0;
                
                const chunk_size = 10 * 1024 * 1024;
                // const chunk_size = 1024;
                const total_chunks = Math.ceil(file.size / chunk_size);
                
                for (let current_chunk = 0; current_chunk < total_chunks; current_chunk++) {
                    const start = current_chunk * chunk_size;
                    const end = Math.min(start + chunk_size, file.size);
                    const chunk = file.slice(start, end);

                    const form_data = new FormData();
                    form_data.append('file_chunk', chunk);
                    form_data.append('file_name', file.name);
                    form_data.append('chunk', current_chunk);
                    form_data.append('total_chunks', total_chunks);

                    await fetch('./upload.php', {
                        method: 'POST',
                        body: form_data,
                    });

                    uploading_progress = (current_chunk + 1) * 100 / total_chunks;
                }

                is_uploading_map = false;
                uploading_progress = 0;
                form.mapname = file.name;
                "
            />
        </label>

        <div>
            <div 
                style="
                background-color: #9b9b9b;
                height: 2px;
                border: 1px solid black;
                border-radius: 2px;
                "
            > 
                <div
                    :style="
                    `transition: width 1s; 
                    width: ${uploading_progress}%; 
                    height: 2px; 
                    background-color: gold;`
                    "
                    x-show="is_uploading_map"
                >
                </div>
            </div>
        </div>
        
        <label>
            O busca uno de nuestros mapas alojados:
            <input id="map_term" x-model.debounce="map_term" placeholder="Islas eco..." />
        </label>
        <select 
            name="mapname" 
            id="mapname" 
            size="10" 
            x-model="form.mapname"
            x-on:change="selected_map = maps.find(map => map.mapa === form.mapname)"
        >
            <template x-for="map in maps">
                <option x-bind:id="'mapa-' + map.mapa" x-bind:value="map.mapa" x-html="map.nombre"></option>
            </template>
        </select>
    </div>
    
    <div style="text-align: center;">
        <div style="position: relative; margin-bottom: 50px;">
            <img
                id="map_preview"
                width="302px" 
                src="./img/minmap.png"
                alt="Vista previa del mapa seleccionado"
                x-on:error="event.target.src = './img/minmap.png'"
                x-bind:src="map_preview"
                x-effect="
                    // Set map preview when selected map gets updated
                    map_preview = selected_map.mapa ? './PHP-MPQ/thumbnail.php?map=' + selected_map.mapa : './img/minmap.png';
                "
            >
            <button 
                :disabled="!(form.name && form.owner && form.mapname) || is_uploading_map"
                type="submit"
                id="create_game_button"
            >
                Crear
            </button>
        </div>

        <h2 id="selected_map_nombre" style="font-weight: normal;" x-html="selected_map.nombre"></h2>
        <h3 id="selected_map_autor" style="font-weight: normal;" x-html="selected_map.autor"></h3>

        <p id="selected_map_desc" style="text-align: left; font-size: 16px;" x-html="selected_map.desc"></p>
    </div>
</form>

</div>

<?php
    $content = ob_get_clean();
    include "index.php";
?>
