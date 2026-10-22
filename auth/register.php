<?php
require_once '../config/parameters.php';

$mensaje = '';
$mensajeClase = 'text-red-500';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($email === '' || $nombre === '' || $dni === '' || $contrasena === '') {
        $mensaje = 'Debés completar todos los campos.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $mensaje = 'Ingresá un email válido.';
    } else {
        require_once __DIR__ . '/../api/conexion.php';
        $rol = 'paciente';
        $nombre = ucfirst($nombre);

        try {
            $conexion->begin_transaction();

            $consultaUsuario = $conexion->prepare(
                'INSERT INTO usuarios (email, nombre, contrasena, rol) VALUES (?, ?, ?, ?)'
            );
            $contrasenaHashed = password_hash($contrasena, PASSWORD_DEFAULT);
            $consultaUsuario->bind_param('ssss', $email, $nombre, $contrasenaHashed, $rol);
            $consultaUsuario->execute();
            $usuarioId = $consultaUsuario->insert_id;
            $consultaUsuario->close();

            $consultaPaciente = $conexion->prepare(
                'INSERT INTO pacientes (usuario_id, dni) VALUES (?, ?)'
            );
            $consultaPaciente->bind_param('is', $usuarioId, $dni);
            $consultaPaciente->execute();
            $consultaPaciente->close();

            $conexion->commit();
            header('Location: ' . base_url . 'auth/login.php?registro=1');
            exit;
        } catch (mysqli_sql_exception $error) {
            $conexion->rollback();
            $mensaje = 'No se pudo crear la cuenta. Verificá que el email y el DNI no estén registrados.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorio</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="<?= base_url ?>assets/css/global.css">
    <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js" defer></script>
</head>

<body class="bg-[url('<?= base_url ?>assets/img/arg-salud-2.jpg')] bg-cover bg-center bg-no-repeat">
    <div class="flex justify-center items-center min-h-screen w-full bg-black/50 px-4">

        <div class="flex flex-col justify-center items-center w-full max-w-sm rounded-xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg text-white p-8">
            <h2 class="text-xl mb-4 font-semibold">Crear Cuenta</h2>
            <p class="<?= $mensajeClase ?>" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
            <div class="w-full">
                <form method="post" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-sm">Email</label>
                        <input type="email" name="email" id="email" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>
                  
                    <div class="flex flex-col gap-1">
                        <label for="nombre" class="text-sm">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>
                  
                    <div class="flex flex-col gap-1">
                        <label for="dni" class="text-sm">DNI</label>
                        <input type="text" name="dni" id="dni" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="contrasena" class="text-sm">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <a href="<?= base_url ?>auth/login.php" class="cursor-pointer text-blue-600 hover:text-blue-800 hover:underline">¿Ya tienes cuenta? Ingresa aquí</a>

                    <button type="submit" class="mt-2 py-2 bg-[#2196F3]/30 hover:bg-[#2196F3]/40 transition-colors rounded-md text-sm font-semibold border border-[#2196F3]/10">
                        Crear cuenta
                    </button>
                </form>
            </div>
        </div>

    </div>

</body>

</html>