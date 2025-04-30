<?php
// Conexión a la base de datos//
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_db";

// Crear la conexión//
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión//
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE username='$usuario' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "¡proceso exitoso!";
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}

$conn->close();
?>
