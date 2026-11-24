<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

$doctor_id = (int) ($_GET['id'] ?? 0);
if ($doctor_id <= 0) {
    header('Location: doctores.php');
    exit;
}

$stmt = $conexion->prepare(
    "SELECT d.id, d.usuario_id, u.nombre, u.email
     FROM doctores d
     INNER JOIN usuarios u ON u.id = d.usuario_id
     WHERE d.id = ? AND u.rol = 'doctor'"
);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$doctor) {
    header('Location: doctores.php?error=' . urlencode('El doctor no existe.'));
    exit;
}

$inputClass = "w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 placeholder-gray-400 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20";
$labelClass = "block text-left text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1";
?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Editar doctor</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Modificá el nombre y el correo del doctor.</p>
    </div>

    <form action="actualizarDoctor.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">

        <input type="hidden" name="id" value="<?= (int) $doctor['id'] ?>">
        <input type="hidden" name="usuario_id" value="<?= (int) $doctor['usuario_id'] ?>">

        <?php if (!empty($_GET['error'])): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="25" autocomplete="name"
                   value="<?= htmlspecialchars($doctor['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="Nombre y apellido" class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="email" class="<?= $labelClass ?>">Email</label>
            <input type="email" name="email" id="email" required autocomplete="email"
                   value="<?= htmlspecialchars($doctor['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="doctor@correo.com" class="<?= $inputClass ?>">
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="doctores.php"
               class="rounded-lg border border-gray-300 px-4 py-2 text-gray-600 transition hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="cursor-pointer rounded-lg bg-(--color-paciente-primary) px-5 py-2 font-semibold text-white shadow-sm transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-(--color-paciente-primary)/40">
                Guardar
            </button>
        </div>
    </form>
</main>