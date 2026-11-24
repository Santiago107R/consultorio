<?php
require_once './layouts/header.php';

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

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Crear servicio</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Completá los datos y el horario de atención.</p>
    </div>

    <form action="guardarServicio.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">
        <?php if (!empty($_GET['error'])): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="255"
                   placeholder="Ej: Laboratorio de análisis clínicos" class="<?= $inputClass ?>">
        </div>

        <fieldset>
            <legend class="<?= $labelClass ?>">Días laborables</legend>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <?php foreach ($diasSemana as $numeroDia => $nombreDia): ?>
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-gray-700 hover:bg-gray-50">
                        <input type="checkbox" name="dias[]" value="<?= $numeroDia ?>">
                        <span><?= htmlspecialchars($nombreDia, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="hora_inicio" class="<?= $labelClass ?>">Hora de inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" required class="<?= $inputClass ?>">
            </div>
            <div>
                <label for="hora_fin" class="<?= $labelClass ?>">Hora de fin</label>
                <input type="time" name="hora_fin" id="hora_fin" required class="<?= $inputClass ?>">
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="servicios.php"
               class="rounded-lg border border-gray-300 px-4 py-2 text-gray-600 transition hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="cursor-pointer rounded-lg bg-(--color-paciente-primary) px-5 py-2 font-semibold text-white shadow-sm transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-(--color-paciente-primary)/40">
                Crear
            </button>
        </div>
    </form>
</main>