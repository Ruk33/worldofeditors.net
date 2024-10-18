<?php 
    $funcion=$_GET['funcion'];
    if($funcion=="listar"){
        $nombre=preg_quote($_GET["nombre"]);
        $tipo=preg_quote($_GET["tipo"]);
        $orden=preg_quote($_GET["orden"]);

        $array = array();
        chdir("../");
        $file = fopen("storage/mapas.csv", 'r');        
        while ((($mapa = fgetcsv($file, 1000, ';')) !== FALSE)) {
            if($tipo=="ALL") {
                if($nombre==""){                      
                    $jsar=[];                
                    $jsar["mapa"]=$mapa[0];
                    $jsar["peso"]=$mapa[3];
                    $jsar["nombre"]=$mapa[1];
                    $jsar["jcj"]=$mapa[2];    
                    $jsar["desc"]=$mapa[6];    
                    $jsar["autor"]=$mapa[4];
                    $jsar["minimap"]=$mapa[8]; 
                    $jsar["jp"]=$mapa[5];   
                    $jsar["id"]=$mapa[9]; 
                    if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO"; 
                    array_push($array, $jsar);
                }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
                    $jsar=[];                
                    $jsar["mapa"]=$mapa[0];
                    $jsar["peso"]=$mapa[3];
                    $jsar["nombre"]=$mapa[1];
                    $jsar["jcj"]=$mapa[2];  
                    $jsar["desc"]=$mapa[6]; 
                    $jsar["autor"]=$mapa[4];
                    $jsar["minimap"]=$mapa[8];   
                    $jsar["jp"]=$mapa[5]; 
                    $jsar["id"]=$mapa[9];  
                    if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO"; 
                    array_push($array, $jsar);
                }
            }elseif($mapa[7]==$tipo){
                if($nombre==""){                      
                    $jsar=[];                
                    $jsar["mapa"]=$mapa[0];
                    $jsar["peso"]=$mapa[3];
                    $jsar["nombre"]=$mapa[1];
                    $jsar["jcj"]=$mapa[2];    
                    $jsar["desc"]=$mapa[6];    
                    $jsar["autor"]=$mapa[4];
                    $jsar["minimap"]=$mapa[8]; 
                    $jsar["jp"]=$mapa[5]; 
                    $jsar["id"]=$mapa[9];
                    if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO";           
                    array_push($array, $jsar);
                }elseif (preg_match("/{$nombre}/i", $mapa[1])) {
                    $jsar=[];                
                    $jsar["mapa"]=$mapa[0];
                    $jsar["peso"]=$mapa[3];
                    $jsar["nombre"]=$mapa[1];
                    $jsar["jcj"]=$mapa[2];  
                    $jsar["desc"]=$mapa[6]; 
                    $jsar["autor"]=$mapa[4];
                    $jsar["minimap"]=$mapa[8];   
                    $jsar["jp"]=$mapa[5];  
                    $jsar["id"]=$mapa[9];
                    if(file_exists("maps/".$mapa[0])) $jsar["fecha"]=date("d-m-Y H:i:s",filectime("maps/".$mapa[0])); else $jsar["fecha"]="DESCONOCIDO";  
                    array_push($array, $jsar);
                }
            }
        }
        fclose($file);
        echo json_encode($array);
    }else if($funcion=="borrar"){
        $mapa=preg_quote(urlencode($_GET["mapa"]));
        chdir("../");
        //echo $mapa;
        $datos = [];
        if (($gestor = fopen("storage/mapas.csv", 'r')) !== FALSE) {
            while (($fila = fgetcsv($gestor, 1000, ';')) !== FALSE) {
                if($fila[9]!=$mapa){                                       
                    $datos[] = $fila; 
                } else echo $mapa;            
            }
            fclose($gestor);
        }
        if (($gestor = fopen("storage/mapas.csv", 'w')) !== FALSE) {
            foreach ($datos as $fila) {
                $linea= $fila[0].";".$fila[1].";".$fila[2].";".$fila[3].";".$fila[4].";".$fila[5].";".$fila[6].";".$fila[7].";".$fila[8].";".$fila[9];
                fputs($gestor, $linea.PHP_EOL);
            }
            fclose($gestor);
        }
    }else if($funcion=="actualizar"){
        chdir("../");
        
        $mapa="";
        $nombre=str_replace(";",",",$_POST['nombre']); 
        $jcj=str_replace(";",",",$_POST['jcj']); 
        $peso="";
        $autor=str_replace(";",",",$_POST['autor']);  
        $jp=str_replace(";",",",$_POST['jp']);  
        $desc=str_replace(";",",",str_replace("\r"," ",str_replace("\n",' ',$_POST['desc'])));  
        $tipo=str_replace(";",",",$_POST['tipo']); 
        $preview="minimap.png"; 
        $id=preg_quote(urlencode($_GET["mapa"])); 

        $datos = [];
        $nueval ="";
        if (($gestor = fopen("storage/mapas.csv", 'r')) !== FALSE) {
            while (($fila = fgetcsv($gestor, 1000, ';')) !== FALSE) {
                if($fila[9]==$id){  
                    if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
                        $mapa=str_replace(";",",",$_FILES["archivo"]['name']);
                        $peso=$_FILES["archivo"]['size'];
                        if (file_exists("maps/".$fila[0])){
                            unlink("maps/".$fila[0]);
                        }  
                        echo $nueval= "".$mapa.";".$nombre.";".$jcj.";".$peso.";".$autor.";".$jp.";".$desc.";".$tipo.";".$preview.";".$id;                      
                    }else{
                        $mapa=$fila[0];
                        $peso=$fila[3];
                        $nfila=array();
                        $nfila[0]=$mapa;
                        $nfila[1]=str_replace(";",",",$nombre);
                        $nfila[2]=str_replace(";",",",$jcj);
                        $nfila[3]=$peso;
                        $nfila[4]=str_replace(";",",",$autor);
                        $nfila[5]=str_replace(";",",",$jp);
                        $nfila[6]=str_replace(";",",",$desc);
                        $nfila[7]=str_replace(";",",",$tipo);
                        $nfila[8]=str_replace(";",",",$preview);
                        $nfila[9]=$id;
                        print_r( $nfila);
                        $datos[] = $nfila;
                    }
                }else{
                    $datos[] = $fila; 
                }                
            }
            fclose($gestor);
        }
        if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
            $file_name = $_FILES['archivo']['name'];
            $tmp_name = $_FILES['archivo']['tmp_name'];
            $UniqueName = $file_name;
            $Upload_Path = "maps/". $UniqueName;
            move_uploaded_file($tmp_name, $Upload_Path);
        }
                
        if (($gestor = fopen("storage/mapas.csv", 'w')) !== FALSE) {
            if(isset($_FILES["archivo"]) && $_FILES['archivo']['name'] != null){
                fputs($gestor, $nueval.PHP_EOL);
            }
            foreach ($datos as $fila) {
                $linea= $fila[0].";".$fila[1].";".$fila[2].";".$fila[3].";".$fila[4].";".$fila[5].";".$fila[6].";".$fila[7].";".$fila[8].";".$fila[9];
                fputs($gestor, $linea.PHP_EOL);
            }
            fclose($gestor);
        }
    }




    
?>