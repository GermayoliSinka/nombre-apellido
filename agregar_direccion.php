<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $direccion = $_POST['direccion']; // Dirección ingresada por el usuario
    $ubicacion = $_POST['ubicacion']; // Coordenadas del mapa seleccionadas por el usuario
    $celular = $_POST['celular'];
    $id_departamento = $_POST['id_departamento'];
    $id_provincia = $_POST['id_provincia'];
    $id_usuario = $_SESSION['id_usuario'];

    $sql = "INSERT INTO libreta_direcciones (nombre, nit, razon_social, direccion, ubicacion, celular, id_departamento, id_provincia, id_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiis", $nombre, $nit, $razon_social, $direccion, $ubicacion, $celular, $id_departamento, $id_provincia, $id_usuario);
    $stmt->execute();

    header("Location: libreta_ubicaciones.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Dirección</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
    <script>
        let map, marker;

        // Función para inicializar Google Maps
        function initMap() {
            // Crear el mapa centrado en La Paz
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -16.500000, lng: -68.150002 },
                zoom: 14,
            });

            // Detectar clics en el mapa para colocar el marcador
            map.addListener("click", (event) => {
                placeMarker(event.latLng);
            });
        }

        // Función para colocar el marcador y guardar las coordenadas
        function placeMarker(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }

            // Guardar las coordenadas seleccionadas en el campo oculto
            document.getElementById('ubicacion').value = `${location.lat()}, ${location.lng()}`;
        }

        // Función para cargar provincias y centrar el mapa en el departamento seleccionado
        function cargarProvincias(idDepartamento) {
            if (!idDepartamento) {
                document.getElementById('id_provincia').innerHTML = '<option value="">Seleccione una provincia</option>';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "obtener_provincias.php?id_departamento=" + idDepartamento, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('id_provincia').innerHTML = xhr.responseText;
                }
            };
            xhr.send();

            // Centrar el mapa en el departamento seleccionado
            centrarMapaEnDepartamento(idDepartamento);
        }

        // Centrar el mapa basado en el departamento seleccionado
        function centrarMapaEnDepartamento(departamentoId) {
            const departamentosCoords = {
                '1': { lat: -16.500000, lng: -68.150002 }, // La Paz
                '2': { lat: -17.393389, lng: -66.145965 }, // Cochabamba
                '3': { lat: -17.783327, lng: -63.182116 }, // Santa Cruz
                '4': { lat: -14.833333, lng: -64.900002 }, // Beni
                '5': { lat: -11.026400, lng: -68.769300 }, // Pando (Cobija)
                '6': { lat: -19.583610, lng: -65.753060 }, // Potosí
                '7': { lat: -19.033320, lng: -65.262740 }, // Chuquisaca (Sucre)
                '8': { lat: -17.983328, lng: -67.150002 }, // Oruro
                '9': { lat: -21.533729, lng: -64.733528 }, // Tarija
            };

            const coords = departamentosCoords[departamentoId];
            if (coords) {
                map.setCenter(new google.maps.LatLng(coords.lat, coords.lng));
            }
        }
    </script>
    <!-- Cargar la API de Google Maps con tu clave API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2sTqxAIFUx4dQhK5jT-C1-yDqOiLJBsI&callback=initMap"></script>
</head>
<body>

<!-- Incluir el menú -->
<?php include 'menu.php'; ?>

<!-- Formulario para agregar dirección -->
<div class="agregar-direccion-container">
    <h2>Añadir Dirección</h2>
    <form action="agregar_direccion.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="nit">NIT:</label>
            <input type="text" name="nit" required>
        </div>
        <div class="form-group">
            <label for="razon_social">Razón Social:</label>
            <input type="text" name="razon_social" required>
        </div>
        <div class="form-group">
            <label for="celular">Celular:</label>
            <input type="text" name="celular" required>
        </div>
        <div class="form-group">
            <label for="id_departamento">Departamento:</label>
            <select name="id_departamento" id="id_departamento" onchange="cargarProvincias(this.value)" required>
                <option value="">Seleccione un departamento</option>
                <?php
                $result_departamentos = $conn->query("SELECT * FROM departamento");
                while ($depto = $result_departamentos->fetch_assoc()) {
                    echo '<option value="' . $depto['id_departamento'] . '">' . $depto['nombre_departamento'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_provincia">Provincia:</label>
            <select name="id_provincia" id="id_provincia" required>
                <option value="">Seleccione una provincia</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required>
        </div>

        <!-- Mapa para seleccionar ubicación -->
        <div id="map"></div>
        <!-- Campo oculto para almacenar las coordenadas seleccionadas -->
        <input type="hidden" name="ubicacion" id="ubicacion" required>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="libreta_ubicaciones.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <p class="info-text">Si no encuentras tu provincia, significa que todavía no podemos realizar envíos a esa ubicación.</p>
</div>

<!-- Incluir el footer -->
<?php include 'footer.php'; ?>

</body>
</html>
