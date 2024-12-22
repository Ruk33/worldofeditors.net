## Instalar

1 - Descargar docker
2 - Crear imagen con `docker build -t world-of-editors .`
3 - Crear contenedor con `docker run -it --rm -p 3000:8080 --name world-of-editors -v .:/var/www/html/public world-of-editors`

## Carpetas

- storage: Para guardar cualquier tipo de archivos que necesitemos (por ejemplo, mapas.csv)
- maps: Donde se van a guardar los mapas (por ejemplo, (2)EchoIsles.w3x)
- processed: Partidas que fueron creadas
- pending: Partidas que necesitan ser creadas
- PHP-MPQ/map_info: Informacion generada de los mapas en formato json
- PHP-MPQ/thumbnails: Preview de los mapas generados
