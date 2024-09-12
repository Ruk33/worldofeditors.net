let estchat= false;
let seleccionado="";
window.onload = function() { 
    let form = document.querySelector("form");    
    let subiendo = document.querySelector(".subiendo");
    let creada = document.querySelector(".creada");
    let bar = document.querySelector(".progressbar div");
    bar.style.width="0%";  
    var select = document.getElementById('mapname');
    select.addEventListener('change', function(){
        var selectedOption = this.options[select.selectedIndex];
        seleccionado=''+selectedOption.text.slice(0, -4);
        document.querySelector("#map").value="";
        Resultado();
    });
    document.querySelector("#name").onkeyup = function(){
        Resultado();
    };
    document.querySelector("#owner").onkeyup = function(){
        document.cookie = "owner="+document.querySelector("#owner").value;
        Resultado();
    };
    function Resultado(){
        if(seleccionado!="" && getCookie("cooldown")== undefined) document.querySelector("button").classList.add("buttonAct");
        document.querySelector(".submapa").innerHTML=""+seleccionado.toUpperCase();
        document.querySelector(".subname").innerHTML=document.querySelector("#name").value.toUpperCase();
        document.querySelector(".subcontrol").innerHTML=document.querySelector("#owner").value.toUpperCase();
        document.querySelector(".desname").innerHTML=document.querySelector("#name").value.toUpperCase();
        document.querySelector(".desowner").innerHTML=document.querySelector("#owner").value.toUpperCase();
        document.querySelector(".desmapa").innerHTML=seleccionado.toUpperCase();
    }
    document.querySelector("#map").addEventListener( 'change', function( e )    {
        document.querySelector(".subiendo p").innerHTML="maps/"+this.files[0].name;
        seleccionado=''+this.files[0].name.slice(0, -4);
        document.querySelector("select").value="";        
        Resultado();
    });
      
    if(getCookie("cooldown")!= undefined){
        let id = setInterval(() => { 
            let val =new Date();
            val= Math.trunc( (getCookie("cooldown") - val.getTime() )/1000);
            document.querySelector(".cooldown").innerHTML="("+val+")";
            if(getCookie("cooldown")== undefined){                
                document.querySelector(".cooldown").innerHTML="";
                if(seleccionado!="") document.querySelector("button").classList.add("buttonAct");
                clearInterval(id);
            }            
        }, 1000);
    }
    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }
//////////////////////////////////////////////////////////////////////////////////////////////////
//////// BUSQUEDA ////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
    document.querySelector("#buscar").onkeyup = function(){
        if(this.value!=""){
            let opciones = document.querySelectorAll("option");
            opciones.forEach((userItem) => {                
                if(userItem.value.toUpperCase().includes(this.value.toUpperCase())){
                    userItem.style.display="block";
                    //console.log(userItem.value+" = "+this.value);
                }else{
                    userItem.style.display="none";
                }
            });
        }else{            
            let opciones = document.querySelectorAll("option");
            opciones.forEach((userItem) => {
                userItem.style.display="block";
            });
        }    
    };
//////////////////////////////////////////////////////////////////////////////////////////////////
//////// ENVIAR FORM /////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
    form.addEventListener("submit", (event) => {
        
        event.preventDefault();
        document.querySelector(".alert").style.display = "inherit";
        let xhr = new XMLHttpRequest();
        xhr.open("POST","maps.php");
        xhr.upload.addEventListener("progress", ({loaded, total}) =>{
            //let fileLoaded = Math.floor((loaded / total) * 100);
            let fileLoaded = ((loaded / total) * 100).toFixed(2);
            let fileTotal = Math.floor(total / 1000);
            console.log(fileLoaded,fileTotal);
            creada.style.display="none";
            subiendo.style.display="inherit";
            bar.style.width=fileLoaded+"%";
            let por = document.querySelector(".progressbar h4");
            por.innerHTML=fileLoaded+"%";
            if(loaded==total){
                creada.style.display="inherit";
                subiendo.style.display="none";
                //cooldown
                let date=new Date();
                date = date.getTime()+181000;
                document.cookie="cooldown="+date+";max-age=180";
                if(getCookie("cooldown")!= undefined){
                    document.querySelector("button").classList.remove("buttonAct");
                    let id = setInterval(() => { 
                        let val =new Date();
                        val= Math.trunc( (getCookie("cooldown") - val.getTime() )/1000);
                        document.querySelector(".cooldown").innerHTML="("+val+")";
                        if(getCookie("cooldown")== undefined){                
                            document.querySelector(".cooldown").innerHTML="";
                            if(seleccionado!="") document.querySelector("button").classList.add("buttonAct");
                            clearInterval(id);
                        }            
                    }, 1000);
                }
            }
        });
        let formData=new FormData(form);
        xhr.send(formData);
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////
//////// MODALES /////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
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
function offmodal(){
    document.querySelector(".alert").style.display = "none";
    document.querySelector(".hispar").style.display = "none"; 
}
function historial(item){
    document.querySelector(".hispar").style.display = "inherit";    
}
//////////////////////////////////////////////////////////////////////////////////////////////////
//////// CARGAR MAPAS ////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
function cargarmapas(){
    document.getElementById('buscar').value="";
    //document.getElementById('mapname').innerHTML="";
    let opciones = document.querySelectorAll("option");
    opciones.forEach((userItem) => {
        userItem.style.display="block";
    });
}



  

 



