window.onload= function(){    
    if (window.innerWidth <= 450) {
        console.log("Es un móvil");
        document.querySelector(".menu").style.marginLeft="-9px";
    }else{
        console.log("windosws")
    }
    



    
    document.querySelector("#help").onclick = function(){
        document.querySelector(".help").style.display="block";
        document.querySelector("#ventanaModal").style.display="block";
    }
    document.querySelector("#descargar").onclick = function(){
        document.querySelector(".descargar").style.display="block";
        document.querySelector("#ventanaModal").style.display="block";
    }
    document.querySelector("#mapas").onclick = function(){
        document.querySelector(".mapas").style.display="block";
        document.querySelector("#ventanaModal").style.display="block";
    }
    document.querySelector(".cerrar").onclick = function(){cerrar()};
    
    function cerrar() {
        document.querySelector(".help").style.display="none";
        document.querySelector(".descargar").style.display="none";
        document.querySelector(".mapas").style.display="none";
        document.querySelector("#ventanaModal").style.display="none";
    }    
    document.querySelector("#anim").addEventListener('change', (event) => { 
        var date = new Date();
        date.setTime(date.getTime()+(30*24*60*60*1000));
        var expires = "; expires="+date.toUTCString(); 
        if(document.querySelector("#anim").checked==true){
            document.cookie = "animated=1"+expires+"; path=./";
        }else{
            document.cookie = "animated=0"+expires+"; path=./";
        }  
        location.reload();
    });
    
    cerrar();
    document.querySelector(".load").style.display="none";
}