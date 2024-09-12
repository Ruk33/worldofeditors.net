window.onload= function(){
    document.querySelector(".load").style.display="none";
    document.querySelector("#buscar").onkeyup = function(){Listar();}
    document.addEventListener('input',(e)=>{
        if(e.target.getAttribute('name')=="tipo"){
            console.log(e.target.value);
            Listar();
        }
    });
    document.querySelector("#orden").addEventListener('change', (event) => { Listar();})
    /*setTimeout(() => {
        //document.documentElement.RequestFullScreen()
        //document.documentElement.mozRequestFullScreen()
        document.documentElement.webkitRequestFullScreen();
    }, 500); */
    ///////////////////////////////////////////////
    // BUSCAR /////////////////////////////////////
    ///////////////////////////////////////////////
    function Listar() {
        let nombre= document.getElementById("buscar").value;
        let tipo= document.querySelector('input[name="tipo"]:checked').value;
        let orden= document.getElementById("orden").checked;
        let consulta=new XMLHttpRequest();
        document.getElementById("mapname").innerHTML="";
        consulta.addEventListener("readystatechange",(e)=>{
            if(consulta.readyState !== 4) return;
            //console.log(consulta);
            if (consulta.status>=200 && consulta.status<300) {
                let data;
                if(orden==true) data=JSON.parse(consulta.responseText); else data = sortJSON(JSON.parse(consulta.responseText), 'nombre', 'asc');
                let fragmento=document.createDocumentFragment();
                if(data.length>0){
                    data.forEach((mapa) => {
                        const opciones=document.createElement("option");
                        opciones.innerHTML=mapa.nombre;
                        opciones.classList.add("mapalist");
                        if(mapa.jcj!=0){
                            opciones.classList.add("nunicon");
                            opciones.classList.add("ni"+mapa.jcj);
                        }
                        opciones.value=mapa.mapa;
                        opciones.dataset.jcj=""+mapa.jcj;
                        opciones.dataset.autor=""+mapa.autor;
                        opciones.dataset.nombre=""+mapa.nombre;
                        opciones.dataset.descripcion=""+mapa.desc;
                        opciones.dataset.minimap=""+mapa.minimap;
                        opciones.dataset.jp=""+mapa.jp;
                        fragmento.appendChild(opciones);                    
                    });
                    document.getElementById("mapname").appendChild(fragmento);
                }
            }
        });        
        consulta.open("GET","./libs/maps.php?funcion=listar&nombre="+nombre+"&tipo="+tipo+"&orden="+orden+"");
        consulta.send();        
    }
    document.querySelector("#Refresh").onclick = function(){
        document.getElementById("buscar").value="";
        document.querySelector('#r1').checked=true;
        document.getElementById("orden").checked=false;
        document.querySelector(".miniatura img").src="img/minmap.png";
        Listar();
    };
    ///////////////////////////////////////////////
    // CARGAR /////////////////////////////////////
    ///////////////////////////////////////////////
    document.querySelector("#map").addEventListener( 'change', function( e ){        
        if(this.value!=""){ 
            if(this.files[0].name.slice(-3)=="w3x" || this.files[0].name.slice(-3)=="w3m"){
                document.querySelector("#mapnombre").innerHTML=this.files[0].name;
                document.querySelector(".mapnamespan").classList.add("mapnameselect"); 
                document.querySelector("#mapname").value=""; 
                document.getElementById("similares").innerHTML="";
                let consulta=new XMLHttpRequest();
                consulta.addEventListener("readystatechange",(e)=>{
                    if(consulta.readyState !== 4) return;
                    //console.log(consulta);
                    if (consulta.status>=200 && consulta.status<300) {
                        let data=JSON.parse(consulta.responseText);                        
                        if(data.length>0){
                            let fragmento=document.createDocumentFragment();
                            data.forEach((mapa) => {
                                const opciones=document.createElement("li");
                                opciones.innerHTML=mapa.nombre;
                                opciones.classList.add("listasimilar");     
                                fragmento.appendChild(opciones);                    
                            });
                            document.getElementById("similares").appendChild(fragmento);
                            AbrirModal("igual");
                        }
                    }
                });  
                let nombre=this.files[0].name.slice(0, -4).split(".")[0];
                if(nombre.length>=7) nombre=nombre.slice(0,-4); else if(nombre.length==6) nombre=nombre.slice(0,-3);                
                if(nombre.split(")").length>1) nombre=nombre.split(")")[1];      
                consulta.open("GET","./libs/maps.php?funcion=similar&nombre="+nombre+"");
                consulta.send();   
            }else{
                document.querySelector("#map").value="";
                /*
                let nombre=this.files[0].name.slice(0, -4).split(".")[0];
                if(nombre.length>=7) nombre=nombre.slice(0,-4); else if(nombre.length==6) nombre=nombre.slice(0,-3);                
                if(nombre.split(")").length>1) nombre=nombre.split(")")[1];
                */
            }        
        }else{
            document.querySelector("#mapnombre").innerHTML="No hay ningun mapa cargado.";
            document.querySelector(".mapnamespan").classList.remove("mapnameselect"); 
        }
    });
    ///////////////////////////////////////////////
    // SELECCIONAR ////////////////////////////////
    ///////////////////////////////////////////////
    document.querySelector("#mapname").addEventListener('change', function(){        
        document.querySelector("#map").value="";
        document.querySelector("#mapnombre").innerHTML="No hay ningun mapa cargado.";
        document.querySelector(".mapnamespan").classList.remove("mapnameselect"); 

        var selectedOption = this.options[this.selectedIndex];
        document.querySelector(".detalles h3").innerHTML="<img src='./img/nun/"+selectedOption.dataset.jcj+".png' alt=''>"+selectedOption.dataset.nombre;
        document.querySelector("#autor").innerHTML=""+selectedOption.dataset.autor;
        document.querySelector("#jp").innerHTML=""+selectedOption.dataset.jp;
        document.querySelector(".detalles p").innerHTML=""+selectedOption.dataset.descripcion;
        document.querySelector(".miniatura img").src="https://worldofeditors.net/PHP-MPQ/thumbnail.php?map="+encodeURI(selectedOption.value);
    });
    ///////////////////////////////////////////////
    // ENVIAR FORM ////////////////////////////////
    ///////////////////////////////////////////////
    document.querySelector("form").addEventListener("submit", (event) => {        
        event.preventDefault();
        if(Validar(document.getElementById("name").value,document.getElementById("owner").value)){
            document.querySelector("button").classList.remove("buttonAct");
            let xhr = new XMLHttpRequest();
            xhr.open("POST","libs/maps.php?funcion=crear");
            xhr.upload.addEventListener("progress", ({loaded, total}) =>{
                //let fileLoaded = Math.floor((loaded / total) * 100);
                let fileLoaded = ((loaded / total) * 100).toFixed(2);
                let fileTotal = Math.floor(total / 1000);
                //console.log(fileLoaded,fileTotal);
                AbrirModal("upload");
                document.querySelector(".progressbar div").style.width=fileLoaded+"%";
                document.querySelector(".progressbar h4").innerHTML=fileLoaded+"%";
                if(loaded==total){
                    CerrarModal();
                    document.querySelector(".subname").innerHTML=document.querySelector("#name").value;                    
                    if (document.querySelector("#mapname").value!=""){
                        var infomap = document.querySelector("#mapname").options[document.querySelector("#mapname").selectedIndex];                        
                        document.querySelector(".submapa").innerHTML=""+infomap.dataset.nombre;
                    }else{
                        document.querySelector(".submapa").innerHTML=document.querySelector("#mapnombre").innerHTML;
                    }
                    document.querySelector(".subcontrol").innerHTML=document.querySelector("#owner").value;
                    AbrirModal("creada");
                    //cooldown
                    let date=new Date();
                    date = date.getTime()+181000;//181000
                    document.cookie="cooldown="+date+";max-age=180";//180
                    if(getCookie("cooldown")!= undefined){
                        let id = setInterval(() => { 
                            let val =new Date();
                            val= Math.trunc( (getCookie("cooldown") - val.getTime() )/1000);
                            document.querySelector(".cooldown").innerHTML="("+val+")";
                            if(getCookie("cooldown")== undefined || val <= 0){                
                                document.querySelector(".cooldown").innerHTML="";
                                document.querySelector("#map").value="";
                                document.querySelector("#mapname").value="";
                                document.querySelector("button").classList.add("buttonAct");
                                clearInterval(id);
                            }            
                        }, 1000);
                    }
                }
            });
            let formData=new FormData(document.getElementById("Formulario"));
            if (document.querySelector("#mapname").value!="") {
                var selectedOption = document.querySelector("#mapname").options[document.querySelector("#mapname").selectedIndex];
                formData.append("description", selectedOption.dataset.descripcion);
                formData.append("nombre", selectedOption.dataset.nombre);
            }
            xhr.send(formData);
        }
    });







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
    function Validar(nombre,owner) {
        document.getElementById("name").classList.remove("inputerror");
        document.getElementById("owner").classList.remove("inputerror");
        document.querySelector(".mapas").classList.remove("inputerror");
        if(nombre.trim()!=""){            
            if(owner.trim()!=""){                
                if(document.querySelector("#map").value!="" || document.querySelector("#mapname").value!="" ){
                    return true;

                }else{
                    document.querySelector(".mapas").classList.add("inputerror");
                    return false;
                }
            }else{
                document.getElementById("owner").classList.add("inputerror");
                return false;
            }
        }else{
            document.getElementById("name").classList.add("inputerror");
            return false;
        }        
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
    function AbrirModal(modal) {
        document.getElementById("alert-"+modal).style.display = "block";
        document.getElementById("cerrar").style.display = "block";  
        if(modal=="upload") document.getElementById("cerrar").style.display = "none"; 
        document.getElementById("ventanaModal").style.display = "block";               
    }
    document.querySelector("#cerrar").onclick = function(){CerrarModal()}
    //window.addEventListener("click",function(event) { if (event.target == document.getElementById("ventanaModal")) CerrarModal(); });
    function CerrarModal() {
        document.getElementById("ventanaModal").style.display = "none"; 
        document.getElementById("alert-igual").style.display = "none";   
        document.getElementById("alert-upload").style.display = "none";  
        document.getElementById("alert-creada").style.display = "none";      
    }
    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }
    if(getCookie("cooldown")!= undefined){
        let id = setInterval(() => { 
            let val =new Date();
            val= Math.trunc( (getCookie("cooldown") - val.getTime() )/1000);
            document.querySelector(".cooldown").innerHTML="("+val+")";
            if(getCookie("cooldown")== undefined || val <= 0){                
                document.querySelector(".cooldown").innerHTML="";
                document.querySelector("button").classList.add("buttonAct");
                clearInterval(id);
            }            
        }, 1000);
    }
    Listar()
}