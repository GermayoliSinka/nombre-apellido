<?php
session_start();

// Verificar si el usuario ha iniciado sesión y cargar el nombre del usuario
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de Nosotros</title>
    <link rel="stylesheet" href="estilos.css">

    <style>
        /* Encapsular estilos específicos para la página de Acerca de Nosotros */
        .about-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-container h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #f0c27b;
            padding-bottom: 10px;
        }

        .about-container .logo {
            display: block;
            margin: 0 auto 30px auto;
            max-width: 300px;
        }

        .columnas {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .columna {
            flex: 1;
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .columna:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .columna h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .columna p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .about-container h2 {
            margin-top: 40px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .about-container p {
            font-size: 16px;
            color: #555;
            text-align: center;
            line-height: 1.6;
        }

        .about-container .btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 10px 20px;
            background-color: #f0c27b;
            color: white;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .about-container .btn:hover {
            background-color: #e1b572;
        }
    </style>
</head>
<body>

<!-- Incluir el menú de navegación -->
<?php include 'menu.php'; ?>

<!-- Contenido principal -->
<div class="about-container">
    <h1>Acerca de Nosotros</h1>
    <img src="images/logo.png" alt="Logo de la plataforma" class="logo">

    <div class="columnas">
        <div class="columna">
            <h2>Nuestra Misión</h2>
            <p>Ser la plataforma líder en la promoción y comercialización de productos artesanales hechos a mano por talentosos artesanos de La Paz, Bolivia, brindando una experiencia única de compra y apoyando el desarrollo de las comunidades artesanales.</p>
        </div>

        <div class="columna">
            <h2>Nuestra Visión</h2>
            <p>Consolidarnos como el principal punto de encuentro entre artesanos y compradores, fomentando el comercio justo, la sostenibilidad y la preservación de las tradiciones artesanales a nivel local e internacional.</p>
        </div>
    </div>

    <h2>¡Descubre nuestros productos!</h2>
    <p>Te invitamos a explorar una gran variedad de productos únicos y hechos a mano por artesanos bolivianos. ¡Apoya el talento local y encuentra piezas artesanales únicas para ti o para regalar!</p>

    <a href="index.php" class="btn">Ver Productos</a>
</div>

<!-- Pie de página -->
<?php include 'footer.php'; ?>

</body>
</html>
