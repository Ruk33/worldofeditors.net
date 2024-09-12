<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="color: #d7d7d7;background: #282828;">
    <?php  $lineas = file("log.txt");
        foreach (array_reverse($lineas,true) as $linea) {?>
            <h4><?=$linea?></h4> 
        <?php } ?>
    
    
</body>
</html>