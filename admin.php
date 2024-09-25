<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <title>Administrar</title>
</head>
<body>
    <center>
        <div class="marco opciones">
            <input id="buscar" type="text" placeholder="Buscar un mapa..." >
            <!-- TIPO -->
            <input type="radio" id="r1" name="tipo" value="ALL" checked>
            <label for="r1" class="btnazul lblbtn no-seleccionable" >All</label>
            <input type="radio" id="r2" name="tipo" value="Meele">
            <label for="r2" class="btnazul lblbtn no-seleccionable" >melee</label>
            <input type="radio" id="r3" name="tipo" value="custom">
            <label for="r3" class="btnazul lblbtn no-seleccionable" >custom</label>
            <!-- ORDEN -->
            <input type="checkbox" id="orden" checked>
            <label for="orden" class="btnazul lblbtn no-seleccionable"> </label>
            <!-- REFRESH -->
            <a class="btnazul btnrefresh no-seleccionable" id="Refresh">
                <svg fill="#f4d800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="97px" height="97px" viewBox="0 0 388.409 388.409" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M281.242,55.345c-9.18-65.484-132.804-33.66-168.3-14.076c-44.064,24.48-75.888,69.769-87.516,118.116 c-4.896,20.808-10.404,52.632,0.612,72.828c-11.016,0.611-20.808,3.06-23.868,9.18c-0.612,0.612,0,1.224,0,1.836l0,0 c-2.448,1.836-3.06,4.896-0.612,7.956c20.808,26.316,41.004,54.468,64.872,77.724c3.06,3.061,8.568,3.673,11.628,0 c20.196-23.256,29.988-53.243,44.676-79.56c2.448-0.612,4.284-1.224,6.12-2.448c3.672-2.448,3.672-7.956,0-10.403 c-7.956-4.896-17.748-3.061-26.316-3.061c-0.612,0-0.612,0-1.224,0c-9.18-33.66,5.508-72.216,24.48-100.368 c28.764-42.84,82.008-39.779,124.236-27.54c1.225,0.612,2.448,0,3.672,0c2.448,1.225,6.12-1.224,6.12-4.283 c0-3.061,3.061-7.345,4.284-9.792c2.448-4.896,6.12-9.181,9.18-13.464C278.794,71.869,287.362,63.301,281.242,55.345z"></path> <path d="M388.343,159.385c-0.612-3.672-2.448-6.12-4.896-7.956c-11.628-24.479-34.884-45.899-50.184-68.544 c-2.448-3.672-8.568-5.508-12.24-1.836c-22.032,23.256-42.229,47.736-63.036,72.216c-3.06,3.672,0,7.956,3.06,7.956l0,0 c10.404,6.732,26.929,5.508,41.005,4.284c3.672,44.063-14.076,82.008-52.633,106.487c-33.048,22.645-70.992,12.24-105.875,24.48 c-1.224,0-1.836,0.612-3.06,1.224c-1.224,0.612-1.836,0.612-3.06,1.225c-1.836,0.611-1.836,3.672-0.612,4.896 c-3.672,12.24-4.284,25.704-2.448,38.557c0,0,0,0,0,0.611l0,0c0,0.612,0,1.225,0,1.225c-1.836,1.836-2.448,4.896-0.612,6.731 c-0.612,4.284,3.672,7.344,7.344,7.956c1.224,0,1.836,0,3.06,0c47.124,30.601,115.056,3.672,155.448-28.152 c43.452-34.271,84.456-110.159,64.26-167.075c5.508,0.612,11.016,1.836,16.524,3.06 C385.282,169.177,388.954,163.669,388.343,159.385z"></path> </g> </g></svg>                
            </a>

        </div>
        <div class="marco lista wcsc">
            <ul id="lista1">
            </ul>   
        </div>
    </center>
    <div id="ventanaModal" class="modalcover">
        <div class="modalalert marco">
            <h1>ELIMINAR</h1>
            <p>Esta seguro que desea eliminar <span id="mapaname">MAPA</span> del servidor permanentemente?</p>
            <a id="cerrar" class="btnblue">CANCELAR</a>
            <a id="delok" class="btnred">ELIMINAR</a>
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
        width: 100%;
        height: 100%;
        display: inline-flex;
        flex-direction: column;
        gap: 3px;
        padding: 5px 0px;
        align-items: center;
    }
    .marco{   
        border: 24px solid;
        border-image: url("./img/marco.png");
        border-image-slice: 50;
        border-image-repeat: round;
        background: rgba(19, 17, 17, 0.9);
        display: block;
        border-radius: 29px;
    }
    .opciones{
        color: white;
        display: inline-flex;
        gap: 3px;
    }    
    .opciones input[type=text] {
        background: #000000;
        padding: 5px 10px;
        color: white;
        border: 1px solid #212121;
        flex: auto;
    }
    .opciones input[type=radio]:checked + .lblbtn {
        background-image: url(./img/btnslc.png);
    }
    .opciones input[type=radio]{
        display: none;
    }
    .btnazul {
        background-image: url(./img/btns.png);
        /* background-position: left; */
        background-repeat: round;
        background-size: cover;
        /* height: 20px; */
        color: #eff14c;
        display: inline-block;
        padding: 10px 13px;
        text-decoration: none;
        text-transform: uppercase;
        margin: 0px -1px;
    }
    .opciones input[type=checkbox] {
        display: none;
    }
    .opciones input[type=checkbox] + .lblbtn {
        background-image: url(./img/abc.png);
        width: 25px;
    }
    .opciones input[type=checkbox]:checked + .lblbtn {
        background-image: url(./img/123.png);
    }
    .btnrefresh {
        width: 23px;
        padding: 0px 14px;
    }
    .btnrefresh>svg {
        width: 100%;
        height: 100%;
    }
    .lista{
        height: 100%;
        overflow-y: scroll;
        min-width: 400px;
    }
    
    ul>li>span{
        margin-right: auto;
    }
    ul>li>label{
        color: #575757;
        font-size: 15px;
        padding-left: 20px;
    }
    li{
        list-style-type: none;
        text-align: left;
        padding: 6px 6px;
        color: white;
        display: flex;
        align-items: center;
        gap: 9px;
    }
    li:nth-child(odd) {
        background-color: #282828;
        border: 2px solid #282828;
    }
    li:nth-child(even) {
        background-color: #1a1a18;
        border: 2px solid #1a1a18;
    }
    li:hover {
        background-color: #150c04;
        border: 2px solid #150c04;
    }

    .nunicon {
        padding-left: 50px;
        background-repeat: no-repeat;
        background-position: left;
        background-size: contain;
        /*padding: 11px 6px;*/
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
        padding: 5px;
        translate: -50%;
        
    }
    .modalalert>h1{
        color: #d94949;
    }
    .modalalert>p{
        padding: 20px 0px;
    }
    .modalalert>p>span{
        color: #287be5;
    }




    .btnred {
        background-image: url(./img/btnred.png);
        background-repeat: round;
        background-size: cover;
        color: #eff14c;
        display: inline-block;
        padding: 10px 30px;
        text-decoration: none;
        text-transform: uppercase;
        /* margin: 0px -1px; */
        font-family: inherit;
        font-size: 1em;
    }
    .btnblue {
        background-image: url(./img/btn.png);
        background-repeat: round;
        background-size: cover;
        color: #eff14c;
        display: inline-block;
        padding: 10px 30px;
        text-decoration: none;
        text-transform: uppercase;
        /* margin: 0px -1px; */
        border: none;
        background-color: inherit;
        font-family: inherit;
        font-size: 1em;
        margin-right: 5%;
    }
    .ni1 {
        background-image: url(./img/nun/1.png);
    }
    .ni2 {
        background-image: url(./img/nun/2.png);
    }
    .ni3 {
        background-image: url(./img/nun/3.png);
    }
    .ni4 {
        background-image: url(./img/nun/4.png);
    }
    .ni5 {
        background-image: url(./img/nun/5.png);
    }
    .ni6 {
        background-image: url(./img/nun/6.png);
    }
    .ni7 {
        background-image: url(./img/nun/7.png);
    }
    .ni8 {
        background-image: url(./img/nun/8.png);
    }
    .ni9 {
        background-image: url(./img/nun/9.png);
    }
    .ni10 {
        background-image: url(./img/nun/10.png);
    }
    .ni11 {
        background-image: url(./img/nun/11.png);
    }
    .ni12 {
        background-image: url(./img/nun/12.png);
    }
    .wcsc::-webkit-scrollbar {
        -webkit-appearance: none;
        width: 17px;
    }
    .wcsc::-webkit-scrollbar-thumb {
        background-color: #073a7b;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
        border: solid 1px #042147;
    }
    .wcsc::-webkit-scrollbar-track {
        background: #020202;
        border-radius: 4px;
        border: solid 2px #605a56;
    }
    .no-seleccionable {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    svg{
        width: 30px;
    }
</style>
<script>
    
window.onload= function(){
    Listar();
    document.querySelector("#buscar").onkeyup = function(){Listar();}
    document.addEventListener('input',(e)=>{ if(e.target.getAttribute('name')=="tipo") Listar(); });
    document.querySelector("#orden").addEventListener('change', (event) => { Listar();});
    document.querySelector("#Refresh").onclick = function(){ document.getElementById("buscar").value=""; document.querySelector('#r1').checked=true; document.getElementById("orden").checked=true; Listar(); };
    function Listar() {
        let nombre= document.getElementById("buscar").value;
        let tipo= document.querySelector('input[name="tipo"]:checked').value;
        let orden= document.getElementById("orden").checked;
        let consulta=new XMLHttpRequest();
        document.getElementById("lista1").innerHTML="";
        consulta.addEventListener("readystatechange",(e)=>{
            if(consulta.readyState !== 4) return;
            //console.log(consulta);
            if (consulta.status>=200 && consulta.status<300) {
                let data;
                if(orden==true) data=JSON.parse(consulta.responseText); else data = sortJSON(JSON.parse(consulta.responseText), 'nombre', 'asc');
                let fragmento=document.createDocumentFragment();
                if(data.length>0){
                    data.forEach((mapa) => {
                        const li_mapa=document.createElement("li");
                        const span_nombre=document.createElement("span");
                        span_nombre.innerHTML=mapa.nombre;
                        li_mapa.appendChild(span_nombre);
                        li_mapa.classList.add("mapalist");
                        if(mapa.jcj!=0){
                            li_mapa.classList.add("nunicon");
                            li_mapa.classList.add("ni"+mapa.jcj);
                        }
                        const label_fecha=document.createElement("label");
                        label_fecha.innerHTML=mapa.fecha;
                        li_mapa.appendChild(label_fecha);
                        const a_link=document.createElement("a");
                        a_link.href="manager.php?map="+mapa.id;
                        a_link.target="_blank";
                        a_link.innerHTML='<svg  viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="#1edc44" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="#1edc44" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="#1edc44" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';
                        li_mapa.appendChild(a_link);
                        const a_copy=document.createElement("a");
                        a_copy.innerHTML='<svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8.18009 16.0199C7.42009 15.9499 6.6701 15.5999 6.0901 14.9899C4.7701 13.5999 4.7701 11.3199 6.0901 9.92989L8.2801 7.6299C9.6001 6.2399 11.7701 6.2399 13.1001 7.6299C14.4201 9.0199 14.4201 11.2999 13.1001 12.6899L12.0101 13.8399" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.8202 7.97998C16.5802 8.04998 17.3302 8.39998 17.9102 9.00998C19.2302 10.4 19.2302 12.68 17.9102 14.07L15.7202 16.37C14.4002 17.76 12.2302 17.76 10.9002 16.37C9.58016 14.98 9.58016 12.7 10.9002 11.31L11.9902 10.16" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';
                        a_copy.onclick=function () {
                            navigator.clipboard.writeText('worldofeditors.net/manager.php?map='+mapa.id);
                        }
                        li_mapa.appendChild(a_copy);
                        const a_info=document.createElement("a");
                        a_info.innerHTML='<svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 17V11" stroke="#ffd700" stroke-width="1.5" stroke-linecap="round"></path> <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#ffd700"></circle> <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="#ffd700" stroke-width="1.5"></path> </g></svg>';
                        a_info.style.display="none";
                        li_mapa.appendChild(a_info);
                        const a_borrar=document.createElement("a");
                        a_borrar.innerHTML='<svg viewBox="0 0 24.00 24.00" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g><g id="SVGRepo_iconCarrier"> <title>delete_back_line</title> <g id="页面-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="System" transform="translate(-382.000000, -192.000000)" fill-rule="nonzero"> <g id="delete_back_line" transform="translate(382.000000, 192.000000)"> <path d="M24,0 L24,24 L0,24 L0,0 L24,0 Z M12.5934901,23.257841 L12.5819402,23.2595131 L12.5108777,23.2950439 L12.4918791,23.2987469 L12.4918791,23.2987469 L12.4767152,23.2950439 L12.4056548,23.2595131 C12.3958229,23.2563662 12.3870493,23.2590235 12.3821421,23.2649074 L12.3780323,23.275831 L12.360941,23.7031097 L12.3658947,23.7234994 L12.3769048,23.7357139 L12.4804777,23.8096931 L12.4953491,23.8136134 L12.4953491,23.8136134 L12.5071152,23.8096931 L12.6106902,23.7357139 L12.6232938,23.7196733 L12.6232938,23.7196733 L12.6266527,23.7031097 L12.609561,23.275831 C12.6075724,23.2657013 12.6010112,23.2592993 12.5934901,23.257841 L12.5934901,23.257841 Z M12.8583906,23.1452862 L12.8445485,23.1473072 L12.6598443,23.2396597 L12.6498822,23.2499052 L12.6498822,23.2499052 L12.6471943,23.2611114 L12.6650943,23.6906389 L12.6699349,23.7034178 L12.6699349,23.7034178 L12.678386,23.7104931 L12.8793402,23.8032389 C12.8914285,23.8068999 12.9022333,23.8029875 12.9078286,23.7952264 L12.9118235,23.7811639 L12.8776777,23.1665331 C12.8752882,23.1545897 12.8674102,23.1470016 12.8583906,23.1452862 L12.8583906,23.1452862 Z M12.1430473,23.1473072 C12.1332178,23.1423925 12.1221763,23.1452606 12.1156365,23.1525954 L12.1099173,23.1665331 L12.0757714,23.7811639 C12.0751323,23.7926639 12.0828099,23.8018602 12.0926481,23.8045676 L12.108256,23.8032389 L12.3092106,23.7104931 L12.3186497,23.7024347 L12.3186497,23.7024347 L12.3225043,23.6906389 L12.340401,23.2611114 L12.337245,23.2485176 L12.337245,23.2485176 L12.3277531,23.2396597 L12.1430473,23.1473072 Z" id="MingCute" fill-rule="nonzero"> </path> <path d="M19,3 C20.5976286,3 21.9036571,4.24892392 21.9949071,5.82372764 L22,6 L22,18 C22,19.597725 20.7510296,20.903664 19.1762674,20.9949075 L19,21 L8.10845,21 C7.13872812,21 6.23313125,20.5316309 5.67200026,19.7503766 L5.56445,19.59 L1.4832,13.06 C1.10904,12.4613846 1.08026698,11.7140828 1.39685735,11.0925926 L1.4832,10.94 L5.56445,4.41 C6.07840625,3.58768125 6.95551777,3.06795234 7.91544369,3.00619841 L8.10845,3 L19,3 Z M19,5 L8.10845,5 C7.80675875,5 7.5236875,5.13599031 7.33521209,5.36585965 L7.26045,5.47 L3.1792,12 L7.26045,18.53 C7.4203475,18.78585 7.68569453,18.9538062 7.98051525,18.9917977 L8.10845,19 L19,19 C19.5127571,19 19.9354959,18.613973 19.9932711,18.1166239 L20,18 L20,6 C20,5.48716857 19.6138867,5.06449347 19.1166055,5.0067278 L19,5 Z M10.8786,8.46447 L12.9999,10.5858 L15.1213,8.46447 C15.5118,8.07394 16.145,8.07394 16.5355,8.46447 C16.926,8.85499 16.926,9.48815 16.5355,9.87868 L14.4142,12 L16.5355,14.1213 C16.926,14.5118 16.926,15.145 16.5355,15.5355 C16.145,15.9261 15.5118,15.9261 15.1213,15.5355 L12.9999,13.4142 L10.8786,15.5355 C10.4881,15.9261 9.85493,15.9261 9.46441,15.5355 C9.07389,15.145 9.07389,14.5118 9.46441,14.1213 L11.5857,12 L9.46441,9.87868 C9.07389,9.48815 9.07389,8.85499 9.46441,8.46447 C9.85493,8.07394 10.4881,8.07394 10.8786,8.46447 Z" id="形状" fill="#ff5c5c"> </path> </g> </g> </g> </g></svg>';
                        a_borrar.dataset.mapid=""+mapa.id;
                        a_borrar.dataset.nombre=""+mapa.nombre;
                        a_borrar.onclick=function () {
                            AbrirModal(this);
                        }
                        li_mapa.appendChild(a_borrar);
                        li_mapa.dataset.jcj=""+mapa.jcj;
                        li_mapa.dataset.autor=""+mapa.autor;
                        li_mapa.dataset.nombre=""+mapa.nombre;
                        li_mapa.dataset.descripcion=""+mapa.desc;
                        li_mapa.dataset.minimap=""+mapa.minimap;
                        li_mapa.dataset.jp=""+mapa.jp;
                        fragmento.appendChild(li_mapa);                    
                    });
                    document.getElementById("lista1").appendChild(fragmento);
                }
            }
        });        
        consulta.open("GET","./libs/administrador.php?funcion=listar&nombre="+nombre+"&tipo="+tipo+"&orden="+orden+"");
        consulta.send();
        
    }
    
    function eliminar(item){
        let consulta=new XMLHttpRequest();
        consulta.addEventListener("readystatechange",(e)=>{
            if(consulta.readyState !== 4) return;
            //console.log(consulta);
            if (consulta.status>=200 && consulta.status<300) {
                Listar();  
                CerrarModal();           
            }
        });        
        consulta.open("GET","./libs/administrador.php?funcion=borrar&mapa="+item.dataset.mapid);
        consulta.send();       
    }
    document.querySelector("#delok").onclick = function(){eliminar(this);}
    document.querySelector("#cerrar").onclick = function(){CerrarModal();}
    function AbrirModal(mapa) { 
        document.getElementById("mapaname").innerHTML=mapa.dataset.nombre;
        document.getElementById("delok").dataset.mapid=mapa.dataset.mapid;
        document.getElementById("ventanaModal").style.display = "block";               
    }
    function CerrarModal() {
        document.getElementById("ventanaModal").style.display = "none";    
    }
    function sortJSON(data, key, orden) {
        return data.sort(function (a, b) {
            var x = a[key],
            y = b[key];
            if (orden === 'asc') {
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            }
            if (orden === 'desc') {
                return ((x > y) ? -1 : ((x < y) ? 1 : 0));
            }
        });
    }
}
</script>
</html>