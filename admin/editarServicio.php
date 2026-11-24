<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$servicioId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$servicioId || $servicioId < 1) {
    header('Location: servicios.php');
    exit;
}

$stmt = $conexion->prepare(
    'SELECT id, nombre, dias_laborables, hora_inicio, hora_fin
     FROM servicios
     WHERE id = ?'
);
$stmt->bind_param('i', $servicioId);
$stmt->execute();
$servicio = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$servicio) {
    header('Location: servicios.php?error=' . urlencode('El servicio no existe.'));
    exit;
}

$diasSeleccionados = array_map('intval', explode(',', $servicio['dias_laborables']));
$diasSemana = [
    0 => 'Domingo',
    1 => 'Lunes',
    2 => 'Martes',
    3 => 'Miércoles',
    4 => 'Jueves',
    5 => 'Viernes',
    6 => 'Sábado',
];
$inputClass = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20';
$labelClass = 'mb-1 block text-left text-xs font-semibold uppercase tracking-wide text-gray-500';
?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Editar servicio</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Modificá el nombre y los horarios disponibles.</p>
    </div>

    <form action="actualizarServicio.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">
        <input type="hidden" name="id" value="<?= (int) $servicio['id'] ?>">

        <?php if (!empty($_GET['error'])): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="255"
                   value="<?= htmlspecialchars($servicio['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $inputClass ?>">
        </div>

        <fieldset>
            <legend class="<?= $labelClass ?>">Días laborables</legend>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <?php foreach ($diasSemana as $numeroDia => $nombreDia): ?>
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-gray-700 hover:bg-gray-50">
                        <input type="checkbox" name="dias[]" value="<?= $numeroDia ?>"
                               <?= in_array($numeroDia, $diasSeleccionados, true) ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($nombreDia, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="hora_inicio" class="<?= $labelClass ?>">Hora de inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" required
                       value="<?= htmlspecialchars(substr($servicio['hora_inicio'], 0, 5), ENT_QUOTES, 'UTF-8') ?>"
                       class="<?= $inputClass ?>">
            </div>
            <div>
                <label for="hora_fin" class="<?= $labelClass ?>">Hora de fin</label>
                <input type="time" name="hora_fin" id="hora_fin" required
                       value="<?= htmlspecialchars(substr($servicio['hora_fin'], 0, 5), ENT_QUOTES, 'UTF-8') ?>"
                       class="<?= $inputClass ?>">
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="servicios.php"
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
