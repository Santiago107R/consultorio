<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$pacienteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$pacienteId || $pacienteId < 1) {
    header('Location: index.php');
    exit;
}

$stmt = $conexion->prepare(
    "SELECT p.id, p.dni, u.nombre
     FROM pacientes AS p
     INNER JOIN usuarios AS u ON u.id = p.usuario_id
     WHERE p.id = ?"
);
$stmt->bind_param('i', $pacienteId);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$paciente) {
    header('Location: index.php?error=' . urlencode('El paciente no existe.'));
    exit;
}

$inputClass = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20';
$labelClass = 'mb-1 block text-left text-xs font-semibold uppercase tracking-wide text-gray-500';
?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Editar paciente</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Modificá el nombre y el DNI del paciente.</p>
    </div>

    <form action="actualizarPaciente.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">
        <input type="hidden" name="id" value="<?= (int) $paciente['id'] ?>">

        <?php if (!empty($_GET['error'])): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="25" autocomplete="name"
                   value="<?= htmlspecialchars($paciente['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="dni" class="<?= $labelClass ?>">DNI</label>
            <input type="text" name="dni" id="dni" required maxlength="100" inputmode="numeric"
                   value="<?= htmlspecialchars($paciente['dni'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $inputClass ?>">
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="index.php"
               class="rounded-lg border border-gray-300 px-4 py-2 text-gray-600 transition hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="cursor-pointer rounded-lg bg-(--color-paciente-primary) px-5 py-2 font-semibold text-white shadow-sm transition hover:opacity-90">
                Guardar
            </button>
        </div>
    </form>
</main>
