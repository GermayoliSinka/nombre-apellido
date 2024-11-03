<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administrador</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e3c7a2;
            --secondary-color: #f9f4ee;
            --text-color: #5e4a3d;
            --accent-color: #d9a773;
            --hover-color: rgba(159, 198, 212, 0.8);
            --active-color: rgba(255, 255, 255, 0.2);
            --success-color: #9ecb8d;
            --highlight-color: #b5838d;
            --button-color: #f4d35e; /* Color amarillo */
            --notification-button-color: #284b63; /* Color azul oscuro */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--notification-button-color);
            padding: 20px 0;
            color: white;
            position: fixed;
            height: 100vh;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(149, 202, 195, 0.1);
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            margin: 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
            transition: background-color 0.3s;
            width: 100%;
        }

        .sidebar ul li a:hover {
            background-color: var(--hover-color);
        }

        .sidebar ul li.active a {
            background-color: var(--active-color);
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: white;
            border-bottom: 1px solid #ccc;
        }

        .welcome {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .profile button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background-color: var(--notification-button-color);
            color: white;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .profile button:hover {
            background-color: var(--accent-color);
        }

        .profile a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: bold;
        }

        h1 {
            margin-bottom: 20px;
            color: var(--highlight-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background-color: var(--highlight-color);
            color: white;
            text-transform: uppercase;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f4ee;
        }

        table tbody tr:hover {
            background-color: var(--hover-color);
        }

        .btn {
            padding: 10px 15px;
            background-color: var(--button-color);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #d4a742;
        }

        .btn-edit {
            background-color: #4caf50;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-delete:hover {
            background-color: #e53935;
        }

        /* Notificaciones */
        .notifications-container {
            position: relative;
            display: inline-block;
            margin-right: 20px;
        }

        .notification-btn {
            background: var(--notification-button-color); /* Color igual al del dashboard */
            border: none;
            cursor: pointer;
            position: relative;
            padding: 10px;
            border-radius: 50%;
            color: white;
            transition: background-color 0.3s;
        }

        .notification-btn:hover {
            background-color: var(--accent-color);
        }

        .notification-btn i {
            font-size: 24px;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px;
            min-width: 18px;
            height: 18px;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            transform: translate(25%, -25%);
            transition: all 0.3s ease;
        }

        .notification-badge.has-notifications {
            animation: pulseAnimation 2s infinite;
        }

        @keyframes pulseAnimation {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar fija -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <img src="../images/image (1).png" style="width: 200px; height: 100px;" />
                <h2>Panel de Control</h2>
            </div>
            <ul>
                <li class="<?php echo !isset($_GET['section']) || $_GET['section'] == 'inicioad' ? 'active' : ''; ?>">
                    <a href="?section=inicioad"><i class="fas fa-home"></i> Home </a>
                </li>
                <li class="<?php echo isset($_GET['section']) && $_GET['section'] == 'usuarios' ? 'active' : ''; ?>">
                    <a href="?section=usuarios"><i class="fas fa-users"></i> Usuarios </a>
                </li>
                <li class="<?php echo isset($_GET['section']) && $_GET['section'] == 'comunidades' ? 'active' : ''; ?>">
                    <a href="?section=comunidades"><i class="fas fa-building"></i> Comunidades</a>
                </li>
                <li class="<?php echo isset($_GET['section']) && $_GET['section'] == 'productos' ? 'active' : ''; ?>">
                    <a href="?section=productos"><i class="fas fa-box"></i> Productos </a>
                </li>
                <li class="<?php echo isset($_GET['section']) && $_GET['section'] == 'comentarios' ? 'active' : ''; ?>">
                    <a href="?section=comentarios"><i class="fas fa-comments"></i> Comentarios </a>
                </li>
            </ul>
        </nav>

        <!-- Área principal donde se mostrará el contenido -->
        <main class="content">
            <header class="header">
                <div class="welcome">
                    Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!
                </div>
                <div class="flex profile-notifications">
                    <div class="notifications-container">
                        <button id="notificationBtn" class="notification-btn" onclick="window.location.href='?section=notificaciones'">
                            <i class="fas fa-bell"></i>
                            <span id="notificationBadge" class="notification-badge <?php echo $no_leidas > 0 ? 'has-notifications' : ''; ?>">
                                <?php echo $no_leidas; ?>
                            </span>
                        </button>
                    </div>
                    <!-- Perfil del usuario -->
                    <div class="profile">
                        <?php if ($profileImage): ?>
                            <a href="?section=perfil">
                                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Perfil">
                            </a>
                        <?php else: ?>
                            <button onclick="window.location.href='?section=perfil'">
                                <?php echo $initials; ?>
                            </button>
                        <?php endif; ?>
                        <a href="../logout.php" class="btn">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </header>

            <!-- Carga dinámica de secciones -->
            <?php
            if (isset($_GET['section'])) {
                $section = $_GET['section'];
                switch ($section) {
                    case 'inicioad':
                        include 'inicioad.php';
                        break;
                    case 'usuarios':
                        include 'usuarios.php';
                        break;
                    case 'comunidades':
                        include 'comunidades.php';
                        break;
                    case 'productos':
                        include 'productos.php';
                        break;
                    case 'comentarios':
                        include 'comentarios.php';
                        break;
                    case 'perfil':
                        include 'perfil.php';
                        break;
                    case 'notificaciones':
                        include 'notificaciones.php';
                        break;
                    default:
                        echo "<h1>Sección no encontrada</h1>";
                }
            }
            ?>
        </main>
    </div>
    <script>
        function updateNotificationCount() {
            fetch('get_notifications_count.php')
                .then(response => response.text())
                .then(count => {
                    const badge = document.getElementById('notificationBadge');
                    const oldCount = parseInt(badge.textContent);
                    const newCount = parseInt(count);

                    badge.textContent = count;

                    // Actualizar la clase para las animaciones
                    if (newCount > 0) {
                        badge.classList.add('has-notifications');
                    } else {
                        badge.classList.remove('has-notifications');
                    }

                    // Si hay nuevas notificaciones, reproducir la animación
                    if (newCount > oldCount) {
                        badge.style.animation = 'none';
                        badge.offsetHeight; // Trigger reflow
                        badge.style.animation = null;
                    }
                })
                .catch(error => console.error('Error actualizando notificaciones:', error));
        }

        // Actualizar cada 30 segundos
        setInterval(updateNotificationCount, 30000);

        // También actualizar inmediatamente al cargar la página
        document.addEventListener('DOMContentLoaded', updateNotificationCount);
    </script>
</body>

</html>
