<!-- header.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Color de fondo */
        }

        :root {
            --pcolor: #ffe799;
            --scolor: rgb(255, 241, 210);
            --bcolor: black;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0; 
            background-color: (255, 241, 210);
        }

        a {
            text-decoration: none;
            color: black;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
            margin-bottom: 50px;
            position: sticky;
            top: 0;
            background: #ffe799;
            z-index: 3;

            .logo {
                padding-left: 50px;

                > a {
                    display: flex;
                    align-items: center;
                    width: 100px;
                    height: 100px;
                    place-content: center;

                    > img {
                        width: 90px;
                        transition: 300ms;

                        &:hover {
                            width: 80px;
                        }
                    }
                }
            }

            .enlaces {
                display: flex;
                gap: 60px;
                padding-right: 50px;

                > a {
                    margin: auto;
                    padding: 0 20px 0 20px;
                    position: relative;
                    transition: ease-in 300ms;

                    &:after {
                        content: "";
                        display: block;
                        width: 0%;
                        height: 3px;
                        background-color: var(--bcolor);
                        border-radius: 5px;
                        position: absolute;
                        bottom: 14px;
                        left: 14px;
                        transition: ease-in 300ms;
                    }

                    &:hover {
                        padding-right: 25px;
                        padding-left: 15px;
                        &:after {
                            width: 78% !important;
                        }
                    }

                    > p {
                        font-size: 18px;
                    }
                }
            }
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 55vh;
        }

        .image-container img {
            width: 500px; /* Tamaño de las imágenes cuadradas */
            height: 400px;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
            margin: 10px; /* Espacio entre las imágenes */
        }

        .image-container img:hover {
            transform: scale(1.1); /* Efecto de escala al pasar el mouse sobre la imagen */
        }

h1{
    text-align: center;
    font-size: 50px;
}
        
    </style>
</head>
<body>
    <header>
        <!-- Contenido del encabezado aquí -->
        <?php include("header.php"); ?>
    </header>


    <h1>TIPOS DE SALA</h1>
    <div class="image-container">
        <a href="modovisual.php?tipo_sala=Comedor" target="_blank">
            <img src="img/comedor.jpg" alt="Enlace a Ejemplo 1">
        </a>
        <a href="modovisual.php?tipo_sala=Sala%20Privada" target="_blank">
            <img src="img/salaprivada.jpg" alt="Enlace a Ejemplo 2">
        </a>
        <a href="modovisual.php?tipo_sala=Terraza" target="_blank">
            <img src="img/terraza.jpg" alt="Enlace a Ejemplo 3">
        </a>
    </div>
</body>
</html>
