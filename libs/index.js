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
        Listar();
        document.querySelector(".mapas").style.display="block";
        document.querySelector("#ventanaModal").style.display="block";
    }
    document.querySelector(".cerrar").onclick = function(){cerrar()};

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
                        
                        const a_copy=document.createElement("a");
                        a_copy.innerHTML='<svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8.18009 16.0199C7.42009 15.9499 6.6701 15.5999 6.0901 14.9899C4.7701 13.5999 4.7701 11.3199 6.0901 9.92989L8.2801 7.6299C9.6001 6.2399 11.7701 6.2399 13.1001 7.6299C14.4201 9.0199 14.4201 11.2999 13.1001 12.6899L12.0101 13.8399" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.8202 7.97998C16.5802 8.04998 17.3302 8.39998 17.9102 9.00998C19.2302 10.4 19.2302 12.68 17.9102 14.07L15.7202 16.37C14.4002 17.76 12.2302 17.76 10.9002 16.37C9.58016 14.98 9.58016 12.7 10.9002 11.31L11.9902 10.16" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#247ff0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';
                        a_copy.onclick=function () {
                            navigator.clipboard.writeText('worldofeditors.net/maps/'+mapa.mapa);
                        }
                        li_mapa.appendChild(a_copy);
                        const a_info=document.createElement("a");
                        a_info.innerHTML='<svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 17V11" stroke="#ffd700" stroke-width="1.5" stroke-linecap="round"></path> <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#ffd700"></circle> <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="#ffd700" stroke-width="1.5"></path> </g></svg>';
                        a_info.style.display="none";
                        li_mapa.appendChild(a_info);
                        
                        const a_downl=document.createElement("a");
                        a_downl.innerHTML='<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V12.1893L14.4697 10.4697C14.7626 10.1768 15.2374 10.1768 15.5303 10.4697C15.8232 10.7626 15.8232 11.2374 15.5303 11.5303L12.5303 14.5303C12.3897 14.671 12.1989 14.75 12 14.75C11.8011 14.75 11.6103 14.671 11.4697 14.5303L8.46967 11.5303C8.17678 11.2374 8.17678 10.7626 8.46967 10.4697C8.76256 10.1768 9.23744 10.1768 9.53033 10.4697L11.25 12.1893V7C11.25 6.58579 11.5858 6.25 12 6.25Z" fill="#fbff29"></path> <path d="M7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H16C16.4142 16.25 16.75 16.5858 16.75 17C16.75 17.4142 16.4142 17.75 16 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z" fill="#fbff29"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25C9.63423 1.24999 7.82519 1.24998 6.4137 1.43975C4.96897 1.63399 3.82895 2.03933 2.93414 2.93414C2.03933 3.82895 1.63399 4.96897 1.43975 6.41371C1.24998 7.82519 1.24999 9.63423 1.25 11.9426V12.0574C1.24999 14.3658 1.24998 16.1748 1.43975 17.5863C1.63399 19.031 2.03933 20.1711 2.93414 21.0659C3.82895 21.9607 4.96897 22.366 6.4137 22.5603C7.82519 22.75 9.63423 22.75 11.9426 22.75H12.0574C14.3658 22.75 16.1748 22.75 17.5863 22.5603C19.031 22.366 20.1711 21.9607 21.0659 21.0659C21.9607 20.1711 22.366 19.031 22.5603 17.5863C22.75 16.1748 22.75 14.3658 22.75 12.0574V11.9426C22.75 9.63423 22.75 7.82519 22.5603 6.41371C22.366 4.96897 21.9607 3.82895 21.0659 2.93414C20.1711 2.03933 19.031 1.63399 17.5863 1.43975C16.1748 1.24998 14.3658 1.24999 12.0574 1.25H11.9426ZM3.9948 3.9948C4.56445 3.42514 5.33517 3.09825 6.61358 2.92637C7.91356 2.75159 9.62177 2.75 12 2.75C14.3782 2.75 16.0864 2.75159 17.3864 2.92637C18.6648 3.09825 19.4355 3.42514 20.0052 3.9948C20.5749 4.56445 20.9018 5.33517 21.0736 6.61358C21.2484 7.91356 21.25 9.62178 21.25 12C21.25 14.3782 21.2484 16.0864 21.0736 17.3864C20.9018 18.6648 20.5749 19.4355 20.0052 20.0052C19.4355 20.5749 18.6648 20.9018 17.3864 21.0736C16.0864 21.2484 14.3782 21.25 12 21.25C9.62177 21.25 7.91356 21.2484 6.61358 21.0736C5.33517 20.9018 4.56445 20.5749 3.9948 20.0052C3.42514 19.4355 3.09825 18.6648 2.92637 17.3864C2.75159 16.0864 2.75 14.3782 2.75 12C2.75 9.62178 2.75159 7.91356 2.92637 6.61358C3.09825 5.33517 3.42514 4.56445 3.9948 3.9948Z" fill="#fbff29"></path> </g></svg>';
                        a_downl.target="_blank";
                        a_downl.href="maps/"+mapa.mapa;
                        li_mapa.appendChild(a_downl);



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
    
    cerrar();
    document.querySelector(".load").style.display="none";
}