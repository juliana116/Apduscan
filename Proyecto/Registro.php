<?php
// verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('❌ Error: Solo se permiten solicitudes POST.');
}
//confirmar datos enviados al servidor
var_dump($_POST);

// Conexion a la base de datos 
$servername="localhost";
$username = "root";
$password = "";
$dbname="registro_usuarios_db";

// Crear conexion
$conn = new mysqli($servername ,$username, $password, $dbname);

// Verificar conexion
if ($conn->connect_error) {
    die(" ❌ Error de conexión: " . $conn->connect_error);
}
//validación de los datos del formulario
if (empty($_POST['nombre_completo']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['Confirmar_contraseña'])) {
echo'⚠️ Todos los campos son obligatorios';
exit;
}
// Obtener y limpiar datos del formulario
$nombre_completo = trim($_POST['nombre_completo']);
$correo = trim($_POST['email']);
$usuario = trim($_POST['username']);
$contraseña = trim($_POST['password']); 
$confirmar_contraseña = trim($_POST['Confirmar_contraseña']);


//valida formato de correo 
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die('⚠️El correo electrónico no es válido');
}
//valida las contraseñas
if ($contraseña !== $confirmar_contraseña) {
    die('⚠️ Las contraseñas no coinciden.');
}
//Encriptar la contraseña
$contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);



// Verificar si el usuario ya existe
$stmt_verificar = $conn->prepare('SELECT ID_usuario FROM formulario_registro_usuarios WHERE usuario = ? OR correo = ?');
$stmt_verificar->bind_param('ss',$usuario,$correo);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    echo "⚠️ El usuario o correo ya está registrado. Intenta con otros datos.";
} else {
    // Insertar datos si no existe
    $stmt_insertar = $conn->prepare('INSERT INTO formulario_registro_usuarios (nombre_completo,correo,usuario,contraseña)  VALUES (?,?,?,?)');
    $stmt_insertar->bind_param('ssss', $nombre_completo,$correo,$usuario,$contraseña_hash);

    if ($stmt_insertar->execute())  {
        echo "✅ Registro exitoso.";
    } else {
        echo "❌ Error al guardar: " . $stmt_insertar->error;
}
    $stmt_insertar->close();
}
// Cerrar conexion
$stmt_verificar->close();
$conn->close();
?>