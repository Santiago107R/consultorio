<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$secretarioId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$secretarioId || $secretarioId < 1) {
    header('Location: secretarios.php');
    exit;
}

$stmt = $conexion->prepare(
    "SELECT id, nombre, email
     FROM usuarios
     WHERE id = ? AND rol = 'secretario'"
);
$stmt->bind_param('i', $secretarioId);
$stmt->execute();
$secretario = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$secretario) {
    header('Location: secretarios.php?error=' . urlencode('El secretario no existe.'));
    exit;
}

$inputClass = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20';
$labelClass = 'mb-1 block text-left text-xs font-semibold uppercase tracking-wide text-gray-500';
?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Editar secretario</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Modificá el nombre y el email de la cuenta.</p>
    </div>

    <form action="actualizarSecretario.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">
        <input type="hidden" name="id" value="<?= (int) $secretario['id'] ?>">

        <?php if (!empty($_GET['error'])): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="25" autocomplete="name"
                   value="<?= htmlspecialchars($secretario['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="email" class="<?= $labelClass ?>">Email</label>
            <input type="email" name="email" id="email" required maxlength="255" autocomplete="email"
                   value="<?= htmlspecialchars($secretario['email'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $inputClass ?>">
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="secretarios.php"
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
