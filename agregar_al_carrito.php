<?php
session_start();

// Incluir la configuración de la base de datos
include 'db_config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(array("status" => "error", "message" => "No has iniciado sesión.")));
}

// Obtener los datos del formulario
$id_producto = $_POST['id_producto'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$id_comunario = $_POST['id_comunario'] ?? '';
$id_usuario = $_SESSION['id_usuario'];

// Verificar que todos los datos necesarios están disponibles
if (empty($id_producto) || empty($cantidad) || empty($id_comunario)) {
    die(json_encode(array("status" => "error", "message" => "Datos incompletos.")));
}

// Obtener el precio y el stock del producto desde la tabla "ofrece" (para precio) y "producto" (para stock)
$sql = "SELECT o.precio, p.stock 
        FROM ofrece o 
        JOIN producto p ON o.id_producto = p.id_producto 
        WHERE o.id_producto = ? AND o.id_comunario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_producto, $id_comunario);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    die(json_encode(array("status" => "error", "message" => "Producto no encontrado.")));
}

// Verificar si hay suficiente stock para la cantidad solicitada
// Primero verificamos si ya hay productos en el carrito
$sql = "SELECT cantidad FROM vende WHERE id_comunario = ? AND id_producto = ? AND id_carrito = (SELECT id_carrito FROM carrito WHERE id_comprador = ? AND estado_carrito = 'Pendiente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_comunario, $id_producto, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$carrito_item = $result->fetch_assoc();

$cantidad_en_carrito = $carrito_item ? $carrito_item['cantidad'] : 0;
$cantidad_total = $cantidad_en_carrito + $cantidad;

// Verificar si la cantidad total excede el stock disponible
if ($cantidad_total > $producto['stock']) {
    die(json_encode(array("status" => "error", "message" => "No hay suficiente stock disponible. Stock actual: " . $producto['stock'])));
}

// Calcular el precio total de la compra
$precio_unitario = $producto['precio'];
$total_precio = $precio_unitario * $cantidad;

// Verificar si el comprador tiene un carrito en estado "Pendiente"
$sql = "SELECT id_carrito FROM carrito WHERE id_comprador = ? AND estado_carrito = 'Pendiente'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$carrito = $result->fetch_assoc();

if ($carrito) {
    // Si existe un carrito en estado "Pendiente", usar ese carrito
    $id_carrito = $carrito['id_carrito'];
} else {
    // Si no existe, crear un nuevo carrito
    $sql = "INSERT INTO carrito (costo_total, fecha, estado_carrito, id_comprador) VALUES (0, NOW(), 'Pendiente', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $id_carrito = $conn->insert_id;
}

if ($carrito_item) {
    // Si el producto ya está en el carrito, actualizar la cantidad
    $sql = "UPDATE vende SET cantidad = ? WHERE id_comunario = ? AND id_producto = ? AND id_carrito = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $cantidad_total, $id_comunario, $id_producto, $id_carrito);
    $stmt->execute();
} else {
    // Si el producto no está en el carrito, agregarlo
    $sql = "INSERT INTO vende (id_comunario, id_producto, id_carrito, cantidad) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $id_comunario, $id_producto, $id_carrito, $cantidad);
    $stmt->execute();
}

// Actualizar el costo total del carrito
$sql = "UPDATE carrito SET costo_total = costo_total + ? WHERE id_carrito = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $total_precio, $id_carrito);
$stmt->execute();

// Cerrar la conexión a la base de datos
$conn->close();

// Enviar un mensaje de éxito como respuesta JSON
$response = array("status" => "success", "message" => "Producto añadido al carrito con éxito!");
echo json_encode($response);
exit();
