<?php 

session_start();

$page_title = "Crear partida de Warcraft 3";

ob_start();

include "../include/js.php";
include "../include/request.php";
include "../include/discord.php";
include "../include/db.php";
include "../include/map_info.php";
include "../include/env.php";
include "../include/create_game.php";
include "../include/create_game_new_bot.php";

if (isset($_POST["submit"])) {
    create_game_new_bot(
        post_value("name"),
        post_value("owner"),
        post_value("uploaded_map", post_value("map_name"))
    );
}

?>

<?php if (discord_is_logged_in()) { ?>

<style>
.jugar-container {
    background-image: url("./img/menuUlt.png");
    width: 1380px;
    height: 997px;
    background-repeat: no-repeat;
    margin-top: -60px;
    padding-top: 60px;
}

.jugar-input {
    border: 0;
    border-style: solid;
    border-radius: 5px;
    border-width: 6px 7px 7px 7px;
    border-image: url("./img/input.png") 10 10 10 10 repeat repeat;
    background-color: #030102;
    color: white;
    font-size: 20px;
    font-family: friz;
    width: 465px;
}

.jugar-input:focus {
    outline: #0d92cb;
    outline-style: solid;
    outline-width: 1px;
}

.jugar-button {
    display: block;
    background-image: url("./img/btn.webp");
    background-repeat: round;
    border-radius: 5px;
    color: gold;
    text-decoration: none;
    padding: 10px;
    padding-left: 50px;
    padding-right: 50px;
    margin-bottom: 5px;
    border: 1px solid #361515;
    box-shadow: 0 0 5px black;
    transition: all 300ms;
    font-size: 16px;
    font-family: friz;
    text-transform: uppercase;
    position: absolute;
    bottom: -30px;
    left: 155px;
    cursor: pointer;
}

.jugar-button:disabled {
    filter: grayscale(100%);
}

.jugar-map-option:hover {
    background-color: #26211d;
}
</style>

<div class="jugar-container">
    <form 
        style="color: white; display: grid; grid-template-columns: 890px 460px; gap: 10px;"
        method="post"
        x-data="{
            maps: [], 
            selected_map: { name: '', author: '', description: '', map_file_name: '', }, 
            map_preview: '',
            map_term: '',
            is_uploading_map: false,
            uploading_progress: 0,

            form: {
                name: '',
                owner: localStorage.getItem('owner') ? localStorage.getItem('owner') : '',
                map_name: '',
                uploaded_map: '',
            },
        }"
        x-effect="
            const result = await fetch('maps.php?nombre=' + encodeURIComponent(map_term) + '&tipo=ALL&orden=false');
            maps = await result.json();
        "
        x-on:submit="localStorage.setItem('owner', form.owner)"
    >
        <!-- form -->
        <div style="padding-left: 40px; padding-right: 10px; padding-top: 50px; padding-bottom: 20px;">
            <div style="padding: 10px;">
                <?php
                    $game_created = isset($_GET["success"]);
                    $game_message = isset($_GET["message"]) ? htmlspecialchars($_GET["message"]) : "???";
                ?>

                <?php if ($game_created) { ?>
                    <h1 style="margin: 0;">Partida creada!</h1>
                    <div>
                        <p style="margin: 0"><?php echo $game_message; ?></p>
                    </div>
                <?php } else { ?>
                    <h1 style="margin: 0;">Crear partida</h1>
                    <div>
                        <p style="margin: 0">Bienvenido <?php echo discord_get_user()->username; ?>!</p>
                    </div>
                <?php } ?>
            </div>

            <div style="margin-top: 25px; padding: 10px;">
                <div>
                    <div style="margin-bottom: 20px;">
                        <label>
                            <div style="color: gold; text-transform: uppercase; font-size: 16px;">Nombre de la partida</div>
                            <input class="jugar-input" id="name" name="name" x-model="form.name" placeholder="Nombre de la partida" maxlength="30" />
                        </label>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>
                            <div style="color: gold; text-transform: uppercase; font-size: 16px;">Jugador que hostea la partida</div>
                            <input class="jugar-input" id="owner" name="owner" x-model="form.owner" placeholder="El nombre del usuario que esta creando la partida" />
                            <p style="margin: 0">Este es el jugador que va a controlar la partida.<br />Podra iniciar la partida con el comando <code>!start</code></p>
                        </label>
                    </div>

                    <label>
                        <div style="color: gold; text-transform: uppercase; font-size: 16px;">Subi un mapa</div>
                        <input
                            class="jugar-input"
                            id="uploaded_map"
                            name="uploaded_map"
                            type="file"
                            :disabled="is_uploading_map"
                            x-model="form.uploaded_map"
                            x-on:change="
                            const files = event.target.files || [];
                            const file = files[0];
                            if (!file)
                                return;

                            is_uploading_map = true;
                            uploading_progress = 0;
                            
                            const chunk_size = 1 * 1024 * 1024;
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

                            // refetch the maps
                            maps = [];
                            const result = await fetch('maps.php?nombre=' + encodeURIComponent(map_term) + '&tipo=ALL&orden=false');
                            maps = await result.json();

                            is_uploading_map = false;
                            uploading_progress = 0;
                            "
                        />
                    </label>

                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <div 
                            style="
                            background-color: #161c1e;
                            height: 23px;
                            border: 1px solid black;
                            border-radius: 2px;
                            box-shadow: 0 0 5px #0a141a inset;
                            "
                        > 
                            <div
                                :style="
                                `transition: width 1s; 
                                width: ${uploading_progress}%; 
                                height: 23px; 
                                background-image: url('./img/loading.png');
                                box-shadow: 0 0 5px #2b7fb0 inset;`
                                "
                                x-show="is_uploading_map"
                            >
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label>
                            <div style="color: gold; text-transform: uppercase; font-size: 16px;">Tambien podes buscar uno de nuestros mapas alojados:</div>
                            <input class="jugar-input" style="width: calc(100% - 18px)" id="map_term" x-model.debounce="map_term" placeholder="Islas eco..." />
                        </label>
                    </div>
                    <input type="hidden" name="map_name" id="map_name" x-model="form.map_name" />
                    <div style="display: flex; flex-direction: column; border-radius: 2px; background-color: black; border: 1px solid gray; padding: 5px; padding-top: 5px; padding-bottom: 10px; height: 360px; overflow-x: hidden; overflow-y: auto; border-radius: 2px;">
                        <template x-for="map in maps">
                            <div 
                                style="display: flex; border: 1px solid #393737; margin-bottom: 5px; padding: 10px; border-radius: 2px;"
                                x-bind:style="selected_map == map ? {border: '1px solid #0d92cb', boxShadow: '0 0 5px #2298ff inset'} : {}"
                                class="jugar-map-option"
                            >
                                <img x-show="(map.max_players || 0) >= 1 && (map.max_players || 0) <= 12" width="32" x-bind:src="'./img/nun/' + map.max_players + '.png'" />
                                <button
                                    type="button"
                                    x-bind:id="'mapa-' + map.name"
                                    x-on:click="
                                        form.map_name = map.map_file_name;
                                        selected_map = map;
                                    "
                                    x-html="map.name"
                                    style="font-family: friz; color: white; text-transform: uppercase; background-color: transparent; border: 0; font-size: 18px; flex: 1; text-align: left;"
                                >
                                </button>
                                <button
                                    type="button"
                                    x-on:click="
                                        const form_data = new FormData();
                                        const id = map.rowid;
                                        form_data.append('id', id);
                                        await fetch('./delete_map.php', {
                                            method: 'POST',
                                            body: form_data,
                                        });
                                        // if the map was deleted, update the map list
                                        // so we don't see it anymore
                                        maps = maps.filter((x) => x.rowid !== id);
                                    "
                                    x-show="map.can_delete"
                                    style="border: 0; background-color: transparent; color: red;"
                                >Borrar</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- map details -->
        <div>
            <div style="text-align: center;">
                <div style="position: relative; margin-bottom: 50px;">
                    <img
                        id="map_preview"
                        width="435px" 
                        src="./img/minmap.png"
                        alt="Vista previa del mapa seleccionado"
                        x-on:error="event.target.src = './img/minmap.png'"
                        x-bind:src="map_preview"
                        x-effect="
                            // Set map preview when selected map gets updated
                            map_preview = selected_map.thumbnail_path ? './storage/' + selected_map.thumbnail_path : './img/minmap.png';
                        "
                        style="margin-top: 40px; border-radius: 15px;"
                    >
                    <button 
                        :disabled="!(form.name && form.owner && (form.map_name || form.uploaded_map)) || is_uploading_map"
                        type="submit"
                        id="submit"
                        name="submit"
                        class="jugar-button"
                    >
                        Crear
                    </button>
                </div>

                <h2 id="selected_map_nombre" style="font-size: 32px; color: gold; font-weight: normal; padding-left: 20px; padding-right: 20px; margin: 0; text-overflow: ellipsis; overflow: hidden; width: 425px; white-space: nowrap;" x-html="selected_map.name"></h2>
                <h3 id="selected_map_autor" style="font-weight: normal; padding-left: 20px; padding-right: 20px; margin: 0; text-overflow: ellipsis; overflow: hidden; width: 425px; white-space: nowrap;" x-html="selected_map.author"></h3>

                <p id="selected_map_desc" style="text-align: left; font-size: 16px; padding-left: 20px; padding-right: 20px; overflow: auto; height: 240px; margin-right: 15px;" x-html="selected_map.description"></p>
            </div>
        </div>
    </form>
</div>

<?php } else { ?>

    <h1>Primero ingresa con tu cuenta de Discord</h1>
    <p>Lamentablemente tenemos que hacer esto para prevenir que spammers creen muchas partidas en nuestro servidor...</p>
    <a href="<?php echo discord_login_url(); ?>">Ingresar con Discord</a>

<?php } ?>

<?php
    if (discord_is_logged_in()) {
        $just_content = ob_get_clean();
    } else {
        $content = ob_get_clean();
    }
    include "index.php";
?>
