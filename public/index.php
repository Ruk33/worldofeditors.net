<?php

header("Cache-Control: public, max-age=5, stale-while-revalidate=5");

// Redirect to foroactivo if required.
$request_uri = $_SERVER['REQUEST_URI'];

// Check if the URI matches the /t{number}- pattern
if (preg_match('/^\/t(\d+)-(.+)$/', $request_uri, $matches)) {
    // Extract the values from the pattern
    $id = $matches[1];
    $slug = $matches[2];

    // Build the redirection URL
    $redirect_url = "https://worldofeditors.foroactivo.com/t{$id}-{$slug}";

    // Redirect with a 301 status code
    header("Location: $redirect_url", true, 301);
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        <?php if (isset($page_title)) { ?>
            <?php echo $page_title . " - World of Editors" ?>
        <?php } else { ?>
            World of Editors
        <?php } ?>
    </title>
    <meta name="description" content="Comunidad Latina de Warcraft 3 donde podras encontrar recursos y tutoriales para aprender a crear tus propios mapas usando el editor de mundos, World Edit.">
    
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">

    <style>
        @font-face {
            font-family: "friz";
            src: url("./resources/friz.woff2") format("woff2");
            font-style: normal;
            font-weight: normal;
            font-display: swap;
        }

        * {
            cursor: url("./img/cursor.png"), auto;
        }

        button:hover,
        a:hover,
        input:hover {
            cursor: url("./img/cursor-hover.png"), auto;
        }

        body {
            font-family: friz;
            background-image: url("./img/background-2.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            font-size: 18px;
        }

        .container {
            display: grid;
            grid-template-columns: 365px 1380px;
            margin: 50px auto 0 auto;
            max-width: 1770px;
            gap: 25px;
        }

        .chain {
            background-image: url("./img/chain.webp");
            background-repeat: no-repeat;
            width: 112px;
            height: 233px;
            position: absolute;
            top: -230px;
        }

        nav {
            text-align: center;
            padding: 25px;
            border: 35px solid;
            border-image: url("./img/marco.webp");
            border-image-slice: 79;
            border-image-repeat: round;
            background: rgb(30 26 25 / 60%);
            border-radius: 21px;
            background-image: url("./img/bcmad2.webp");
            background-position: center;
            background-repeat: repeat;
            position: relative;
            text-transform: uppercase;
            font-size: 16px;
        }

        nav > a {
            display: block;
            background-image: url("./img/btn.webp");
            background-repeat: round;
            border-radius: 5px;
            color: gold;
            text-decoration: none;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #361515;
            box-shadow: 0 0 5px black;
            transition: all 300ms;
        }

        nav > a:hover {
            color: white;
        }

        nav > p {
            color: white;
        }

        main {
            padding: 25px;
            border: 35px solid;
            border-image: url("./img/marco.webp");
            border-image-slice: 79;
            border-image-repeat: round;
            background: rgb(12 15 26 / 70%);
            border-radius: 21px;
            position: relative;
            color: antiquewhite;
        }

        main a {
            color: white;
        }

        h1 {
            text-transform: uppercase;
            color: gold;
        }

        h2 {
            color: white;
        }

        @keyframes fall {

          0% {

            opacity: 0;

          }

          50% {

            opacity: 1;

          }

          100% {

            top: 100vh;

            opacity: 1;

          }

        }

        @keyframes sway {

          0% {

            margin-left: 0;

          }

          25% {

            margin-left: 50px;

          }

          50% {

            margin-left: -50px;

          }

          75% {

            margin-left: 50px;

          }

          100% {

            margin-left: 0;

          }

        }

        #snow-container {  

          height: 100vh;

          overflow: hidden;

          position: absolute;

          top: 0;
          left: 0;

          transition: opacity 500ms;

          width: 100%;

        }

        .snow {

          animation: fall ease-in infinite, sway ease-in-out infinite;

          position: absolute;

          user-select: none;

        }

    </style>

    <script>
        // Preload page when hovering a link.
        document.addEventListener("mouseover", (e) => {
            if (!e.target.href) return;
            if (e.target.target) return;
            fetch(e.target.href);
        });

        // Replace page's content when clicking a link preventing a full page reload.
        // document.addEventListener("click", async (e) => {
        //     const link = e.target.href;

        //     if (!link) return;
        //     if (e.target.target) return;

        //     // Don't push the same url multiple times in the history.
        //     if (new URL(link).origin !== location.origin) return;

        //     // Only php link/pages.
        //     if (!link.endsWith(".php")) return;

        //     e.preventDefault();

        //     const result = await fetch(link);
        //     const content = await result.text();

        //     history.pushState({}, "", e.target.href);
        //     document.body.innerHTML = content;
        // });

        // Handle history (back)
        // window.addEventListener("popstate", async (e) => {
        //     const result = await fetch(window.location.href);
        //     const content = await result.text();

        //     document.body.innerHTML = content;
        // });


        const snowContent = ['&#10052', '&#10053', '&#10054']

        const randomForSnow = (num) => {

          return Math.floor(Math.random() * num);

        }

        const getRandomStyles = () => {

          const top = randomForSnow(100);

          const left = randomForSnow(100);

          const dur = randomForSnow(10) + 10;

          const size = randomForSnow(25);

          const colorOptions = ["#cbfffa", "white", "skyblue", "cyan"];

          const color = colorOptions[randomForSnow(4)]


          return `

            top: -${top}%;

            left: ${left}%;
            color: ${color};

            font-size: ${size}px;

            animation-duration: ${dur}s;

            `;

        }

        const createSnow = (num) => {
            const snowContainer =document.getElementById("snow-container");

          for (var i = num; i > 0; i--) {

            var snow = document.createElement("div");

            snow.className = "snow";

            snow.style.cssText = getRandomStyles();

            snow.innerHTML = snowContent[randomForSnow(2)]

            snowContainer.append(snow);

          }

        }

        window.addEventListener("load", () => {

          createSnow(300)

        });


    </script>
</head>
<body>
    <div id="ssnow-container"></div>
    <div class="container">
        <div>
            <nav>
                <div aria-hidden="true" class="chain" style="left: 0;"></div>
                <div aria-hidden="true" class="chain" style="right: 0;"></div>

                <header>
                    <a href="/" style="display: block;">
                        <img width="250" height="152" src="./img/logo-menu-2.webp" alt="Logo de World of Editors">
                    </a>
                </header>

                <a id="menu-jugar" href="/jugar.php">Jugar</a>
                <a id="menu-como-jugar" href="/como-jugar.php">Como Jugar</a>
                <a id="menu-descargas" href="/descargas.php">Descargas</a>
                <!-- <a href="/mapas.php">Mapas</a> -->
                <a id="menu-canal" href="https://www.youtube.com/@WorldOfEditorsOficial/videos" target="_blank" rel="noopener">Youtube</a>
                <a id="menu-discord" href="https://discord.gg/wKrYPqWJCg" target="_blank" rel="noopener">Discord</a>
                <p>
                    World of Editors<br />
                    <span style="font-size: 12px; color: gray;">Original design by Shikuso</span>
                </p>
            </nav>
        </div>

        <?php if (isset($just_content)) { ?>
            <?php echo $just_content ?>
        <?php } else if (isset($content)) { ?>
        <main>
            <?php echo $content ?>
        </main>
        <?php } ?>
    </div>
</body>
</html>
