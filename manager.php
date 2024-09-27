<?php $file = fopen("mapas.csv", 'r');  
    $DATA=[];  
    while ((($mapa = fgetcsv($file, 1000, ';')) !== FALSE)) {
        if ($mapa[9]==urlencode($_GET['map'])) {
            $DATA=$mapa;
            break;
        }
    }
    fclose($file);
    if($DATA==null){
        header("Location: jugar.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <title><?php echo $DATA[1] ?></title>
</head>
<body>
    <center>
        <form method="post" id="Formulario">
            <div class="marco contenedor">
                <div class="cabecera">
                    <img src="https://worldofeditors.net/PHP-MPQ/thumbnail.php?map=<?php echo str_replace(" ","%20",$DATA[0]); ?>" alt="minimapa"  onerror="this.src='img/minmap.png';" />
                    <div class="metadata">
                        <label>NOMBRE DEL MAPA:</label>
                        <input type="text" class="entrada" id="nombre" name="nombre" value="<?php echo $DATA[1]; ?>">
                        <label>AUTOR:</label>
                        <input type="text" class="entrada" id="autor" name="autor" value="<?php echo $DATA[4]; ?>">
                        <label>DESCRIPCION:</label>
                        <textarea id="desc" name="desc" class="entrada"><?php echo $DATA[6]; ?></textarea>
                    </div>
                </div>
                <div class="datos">
                    <label>TIPO:</label>
                    <SELECT class="entrada" id="tipo" name="tipo">
                        <option value="meele" <?php if($DATA[7]=="meele") echo "selected"; ?>>MEELE</option>
                        <option value="custom" <?php if($DATA[7]=="custom") echo "selected"; ?>>CUSTOM</option>
                    </SELECT>
                    <label>JUGADORES:</label>
                    <SELECT class="entrada" id="jcj" name="jcj">
                        <option value="1" <?php if($DATA[2]==1) echo "selected"; ?>>1</option>
                        <option value="2" <?php if($DATA[2]==2) echo "selected"; ?>>2</option>
                        <option value="3" <?php if($DATA[2]==3) echo "selected"; ?>>3</option>
                        <option value="4" <?php if($DATA[2]==4) echo "selected"; ?>>4</option>
                        <option value="5" <?php if($DATA[2]==5) echo "selected"; ?>>5</option>
                        <option value="6" <?php if($DATA[2]==6) echo "selected"; ?>>6</option>
                        <option value="7" <?php if($DATA[2]==7) echo "selected"; ?>>7</option>
                        <option value="8" <?php if($DATA[2]==8) echo "selected"; ?>>8</option>
                        <option value="9" <?php if($DATA[2]==9) echo "selected"; ?>>9</option>
                        <option value="10" <?php if($DATA[2]==10) echo "selected"; ?>>10</option>
                        <option value="11" <?php if($DATA[2]==11) echo "selected"; ?>>11</option>
                        <option value="12" <?php if($DATA[2]==12) echo "selected"; ?>>12</option>                        
                    </SELECT>
                    <label>SUGERIDOS:</label>
                    <input type="text" id="jp" name="jp" class="entrada" value="<?php echo $DATA[5]; ?>">
                </div>  
                <input type="file" name="archivo" id="archivo">
                <label for="archivo" id="filename">ARCHIVO: <?php echo $DATA[0]; ?></label>
                
            </div>
            <div class="marco acciones">
                <a  class="btngreen" onclick="cargar()">CARGAR DATOS DESDE ARCHIVO</a>
                <br><br>
                <input type="submit" class="btnblue" value="GUARDAR CAMBIOS">
                <a  class="btnred" onclick="AbrirModal()">ELIMINAR MAPA</a>
            </div>
        </form>
    </center>
    <div id="ventanaModal" class="modalcover">
        <div class="modalalert marco upload">  
            <h1>SUIENDO MAPA</h1>
            <div class="progressbar">
                <div>
                    <h4>100%</h4>
                </div>
            </div>
            <p>maps/mapa.wx3</p>        
        </div>
        <div class="modalalert marco delete"> 
            <h1>ELIMINAR</h1>
            <p>Esta seguro que desea eliminar <span id="mapaname"><?php echo $DATA[1]; ?></span> del servidor permanentemente?</p>
            <a id="cerrar" class="btnblue" onclick="cancelar()">CANCELAR</a>
            <a id="delok" class="btnred" onclick="eliminar()">ELIMINAR</a>        
        </div>
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
<style>
    *{
        margin: 0;
        padding: 0;
        cursor: url("./img/newcursor.png"),default;
        color: white;
        font-family: Arial, Helvetica, sans-serif;
    }    
    .fondo{
        z-index: -1;
        position: absolute;
        width: 100%;
        top: 0;
        left: 0;
    }
    body{
        background-color: #121f3f;
        overflow: hidden;
        height: 99vh;
    }
    center{
        height: 100%;
    }
    form{
        width: 100%;
        height: 100%;
        display: inline-flex;
        flex-direction: column;
        gap: 25px;
        padding: 5px 0px;
        align-items: center;
    }
    .contenedor{
        max-width: 700px;
    }
    .acciones{
        color: white;
        /*display: inline-flex;*/
        gap: 3px;
    }
    .cabecera{
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .cabecera>img{
        border: 1px solid #c0c0c0;
    }
    .metadata{
        text-align: left;
        display: grid;
        width: 100%;
        padding: 10px 0px 0px 10px;
    }
    #desc{
        height: 90px;
        resize: none;
    }
    .datos{
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cabecera>img{
        width: 256px;
        height: 256px;
    }
    input[type=file]{
        display: none;
    }
    input[type=file] ~ label{
        display: inline-block;
        width: 100%;
        padding: 12px 0px;
        background-color: #150c04;
        margin-top: 10px;
        border: 2px solid #b9c573;
    }
    .modalcover{
        position: fixed;
        background: #000000b5;
        z-index: 2;
        top: 0;
        width: -webkit-fill-available;
        height: -webkit-fill-available;
        left: 0;
        display: none;
    }
    .modalalert{
        position: absolute;
        top: 20%;
        left: 50%;        
        background: #000;
        color: white;
        text-align: center;
        padding: 5px 33px;
        translate: -50%;
        min-width: 400px;
        display: none;
        
    }
    .upload{
        display: none;
    }
    .upload>h1{
        color: #eff14c;
        margin-bottom: 10px;
    }
    .upload>p{
        padding: 10px 0px;
    }
    .progressbar{
        width: 100%;
        height: 35px;
        border: 3px solid #bdbdbd;
        border-radius: 4px;
        position: relative;
        text-align: left;
    }
    .progressbar div {
        background-image: url(./img/pload.png);
        background-position: left;
        background-size: cover;
        height: 100%;
        border-right: 1px solid #3ae0fc;
        width: 0%;
    }
    .progressbar h4 {
        position: absolute;
        top: calc(50% - 9px);
        margin: 0px;
        width: 100%;
        text-align: center;
        color: yellow;
        font-size: 1em;
    }
    .delete{
        display: none;
    }
    .delete>h1{
        color: #d94949;
    }
    .delete>p{
        padding: 20px 0px;
    }
    .delete>p>span{
        color: #287be5;
    }
    .marco {
        border: 24px solid;
        border-image: url(./img/marco.png);
        border-image-slice: 50;
        border-image-repeat: round;
        background: rgba(19, 17, 17, 0.9);
        border-radius: 29px;
    }
    .entrada {
        width: 95%;
        border: 2.5px solid grey;
        border-radius: 4px;
        background: #000000;
        color: white;
        padding: 8px 5px;
        font-size: 1em;
        
    }
    .metadata>.entrada{
        margin-bottom: 8px;
    }
    .btnred{
        background-image: url(./img/btnred.png);
        background-repeat: round;
        background-size: cover;
        color: #eff14c;
        display: inline-block;
        padding: 10px 30px;
        text-decoration: none;
        text-transform: uppercase;
        margin: 0px 5px;
        font-family: inherit;
        font-size: 1em;
    }
    .btngreen{
        background-image: url(./img/btng.png);
        background-repeat: round;
        background-size: cover;
        color: #eff14c;
        display: inline-flex;
        padding: 10px 79px;
        text-decoration: none;
        text-transform: uppercase;
        font-family: inherit;
        font-size: 1em;
        margin-bottom: -23px;
    }
    .btnblue{
        background-image: url(./img/btn.png);
        background-repeat: round;
        background-size: cover;
        color: #eff14c;
        display: inline-block;
        padding: 10px 30px;
        text-decoration: none;
        text-transform: uppercase;
        margin: 0px 5px;
        border: none;
        background-color: inherit;
        font-family: inherit;
        font-size: 1em;        
    }
</style>
<script>
    document.querySelector("#archivo").addEventListener( 'change', function( e ){ 
        if(this.value!=""){ 
            document.querySelector("#filename").innerHTML="ARCHIVO: "+this.files[0].name;
        }else{
            document.querySelector("#filename").innerHTML="ARCHIVO: <?php echo $DATA[0] ?>";
        }

    });
    document.querySelector("form").addEventListener("submit", (event) => {        
        event.preventDefault();

        let xhr = new XMLHttpRequest();
        xhr.open("POST","./libs/administrador.php?funcion=actualizar&mapa=<?php echo $DATA[9] ?>");
        xhr.upload.addEventListener("progress", ({loaded, total}) =>{
            //let fileLoaded = Math.floor((loaded / total) * 100);
            let fileLoaded = ((loaded / total) * 100).toFixed(2);
            let fileTotal = Math.floor(total / 1000);
            //console.log(fileLoaded,fileTotal);
            if(document.querySelector("#archivo").value!=""){
                document.querySelector(".upload").style.display = "block"; 
                document.querySelector(".upload>p").innerHTML="maps/"+document.querySelector("#archivo").files[0].name;
                document.querySelector("#ventanaModal").style.display = "block"; 
            }

            document.querySelector(".progressbar div").style.width=fileLoaded+"%";
            document.querySelector(".progressbar h4").innerHTML=fileLoaded+"%";

            if(loaded==total){
                document.querySelector(".upload").style.display = "none"; 
                document.querySelector("#ventanaModal").style.display = "none";                      
            }
        });
        let formData=new FormData(document.getElementById("Formulario"));            
        xhr.send(formData);        
    });
    function eliminar(){
        let consulta=new XMLHttpRequest();
        consulta.addEventListener("readystatechange",(e)=>{
            if(consulta.readyState !== 4) return;
            //console.log(consulta);
            if (consulta.status>=200 && consulta.status<300) {
                document.querySelector(".delete").style.display = "none";  
                document.getElementById("ventanaModal").style.display = "none"; 
                window.location.href = './jugar.php';              
            }
        });        
        consulta.open("GET","./libs/administrador.php?funcion=borrar&mapa=<?php echo $DATA[9] ?>");
        consulta.send();       
    }
    function AbrirModal() { 
        document.querySelector(".upload").style.display = "none"; 
        document.querySelector(".delete").style.display = "block";  
        document.getElementById("ventanaModal").style.display = "block";               
    }
    function cancelar() {
        document.querySelector(".upload").style.display = "none"; 
        document.querySelector(".delete").style.display = "none";  
        document.getElementById("ventanaModal").style.display = "none"; 
    }
    function cargar(){  
        const request = new XMLHttpRequest();
        request.open("GET", "PHP-MPQ/map_info.php?map=<?php echo str_replace(" ","%20",$DATA[0]); ?>");   
        request.responseType = "json";
        request.send();
        request.onload = function () {
            const datamap = request.response;            
            document.querySelector("#nombre").value=""+datamap["name"];
            document.querySelector("#autor").value=""+datamap["author"];
            document.querySelector("#desc").value=""+datamap["description"];
            document.querySelector("#jp").value=""+datamap["players_recommended"];
        };
    }
</script>
</html>