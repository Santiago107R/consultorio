<?php
require_once './layouts/header.php';

$inputClass = "w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 placeholder-gray-400 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20";
$labelClass = "block text-left text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1";
?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Crear especialidad</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Completá el nombre de la nueva especialidad.</p>
    </div>

    <form action="guardarEspecialidad.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">

        <?php if (!empty($_GET['error'])): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="50"
                   placeholder="Ej: Cardiología" class="<?= $inputClass ?>">
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="especialidades.php"
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