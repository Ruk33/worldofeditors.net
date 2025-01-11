## Instalar

1 - Descargar docker
2 - Correr con `docker-compose up`
3 - Correr migraciones visitando http://localhost:8080/libs/migration.php

## Carpetas

- maps      : Donde se van a guardar los mapas (por ejemplo, (2)EchoIsles.w3x)
- processed : Partidas que fueron creadas
- pending   : Partidas que necesitan ser creadas
- storage   : Para guardar archivos
- public    : Paginas accesibles por los usuarios
- include   : Archivos no accesibles por los usuarios donde podemos almacenar
              funciones utiles (ejemplo: obtener informacion de los mapas)
