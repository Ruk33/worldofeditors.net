let estchat= false;

function conectar(item){
    ocultar();
    var vent = document.querySelector("#conectar");
    vent.classList.add('mostrar');
}
function descargar(item){
    ocultar();
    var vent = document.querySelector("#descargar");
    vent.classList.add('mostrar');
}
function Mapas(item){
    ocultar();
    var vent = document.querySelector("#Mapas");
    vent.classList.add('mostrar');
}
function chat(item){
    if(estchat==true){
        var vent = document.querySelector(".chatfull");
        vent.classList.remove('mostrar');
        estchat=false;
    }else{
        var vent = document.querySelector(".chatfull");
        vent.classList.add('mostrar');
        estchat=true;
    }
}
window.onload = function() {  
    document.querySelector("#buscar").onkeyup = function(){
        if(this.value!=""){
            let mapas = document.querySelectorAll(".mapad");
            mapas.forEach((userItem) => {                
                if(userItem.dataset.mapaname.toUpperCase().includes(this.value.toUpperCase())){
                    userItem.style.display="inline-flex";
                }else{
                    userItem.style.display="none";
                }
            });
        }else{            
            let mapas = document.querySelectorAll(".mapad");
            mapas.forEach((userItem) => {
                userItem.style.display="inline-flex";
            });
        }    
    };    
}
function ocultar(){
    var ventana = document.querySelector("#datos");
    ventana.classList.remove('marco','bg-2');
    ventana.classList.add('marco','bg-2');
    var conectar = document.querySelector("#conectar");
    conectar.classList.remove('mostrar');
    var descargar = document.querySelector("#descargar");
    descargar.classList.remove('mostrar');
    var Mapas = document.querySelector("#Mapas");
    Mapas.classList.remove('mostrar');
    var discord = document.querySelector(".chatfull");
    discord.classList.remove('mostrar');
    estchat=false;
}