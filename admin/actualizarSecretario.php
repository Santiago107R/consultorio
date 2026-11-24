<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: secretarios.php');
    exit;
}

$secretarioId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';
$email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';

function volverAEditarSecretario(string $error, int $secretarioId): void
{
    header('Location: editarSecretario.php?' . http_build_query([
        'id' => $secretarioId,
        'error' => $error,
    ]));
    exit;
}

if (!$secretarioId || $secretarioId < 1) {
    header('Location: secretarios.php');
    exit;
}

if ($nombre === '' || $email === '') {
    volverAEditarSecretario('Completá el nombre y el email.', $secretarioId);
}
if (mb_strlen($nombre) > 25) {
    volverAEditarSecretario('El nombre no puede tener más de 25 caracteres.', $secretarioId);
}
if (mb_strlen($email) > 255 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    volverAEditarSecretario('Ingresá un email válido.', $secretarioId);
}

try {
    $stmtExiste = $conexion->prepare(
        "SELECT id FROM usuarios WHERE id = ? AND rol = 'secretario'"
    );
    $stmtExiste->bind_param('i', $secretarioId);
    $stmtExiste->execute();
    $existe = $stmtExiste->get_result()->num_rows > 0;
    $stmtExiste->close();

    if (!$existe) {
        header('Location: secretarios.php?error=' . urlencode('El secretario no existe.'));
        exit;
    }

    $stmt = $conexion->prepare(
        "UPDATE usuarios
         SET nombre = ?, email = ?
         WHERE id = ? AND rol = 'secretario'"
    );
    $stmt->bind_param('ssi', $nombre, $email, $secretarioId);
    $stmt->execute();
    $stmt->close();

    header('Location: secretarios.php?actualizado=1');
    exit;
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    if ($error->getCode() === 1062) {
        volverAEditarSecretario('Ya existe un usuario con ese email.', $secretarioId);
    }

    volverAEditarSecretario('No se pudieron guardar los cambios. Intentá de nuevo.', $secretarioId);
}
