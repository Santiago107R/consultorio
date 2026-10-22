<?php
require_once '../config/parameters.php';

$mensaje = ($_GET['registro'] ?? '') === '1' ? 'Cuenta creada correctamente. Ya podés iniciar sesión.' : '';
$mensajeClase = 'text-green-500';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($email === '' || $contrasena === '') {
        $mensaje = 'Debés completar email y contraseña.';
        $mensajeClase = 'text-red-500';
    } else {
        require_once __DIR__ . '/../api/conexion.php';
        $consulta = $conexion->prepare(
            'SELECT email, nombre, contrasena, rol FROM usuarios WHERE email = ?'
        );
        $consulta->bind_param('s', $email);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_assoc();
        $consulta->close();

        if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
            $mensaje = 'Email o contraseña incorrectos.';
            $mensajeClase = 'text-red-500';
        } else {
            unset($usuario['contrasena']);
            session_start();
            $_SESSION['usuario'] = $usuario;
            header('Location: ' . base_url);
            exit;
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
            <h2 class="text-xl mb-4 font-semibold">Iniciar Sesión</h2>
            <p class="<?= $mensajeClase ?>" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
            <div class="w-full">
                <form method="post" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-sm">Email</label>
                        <input type="email" name="email" id="email" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="contrasena" class="text-sm">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" required class="bg-white px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <a href="<?= base_url ?>auth/register.php" class="cursor-pointer text-blue-600 hover:text-blue-800 hover:underline">¿No tienes cuenta? Regístrate aquí</a>

                    <button type="submit" class="mt-2 py-2 bg-[#2196F3]/30 hover:bg-[#2196F3]/40 transition-colors rounded-md text-sm font-semibold border border-[#2196F3]/10">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>

    </div>

</body>

</html>