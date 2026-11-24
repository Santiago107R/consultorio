<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$pacienteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$pacienteId || $pacienteId < 1) {
    header('Location: index.php');
    exit;
}

$stmt = $conexion->prepare(
    "SELECT u.nombre, p.dni, p.historia_clinica
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
?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>

<main class="mx-auto w-11/12 max-w-4xl space-y-5 p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Historia clínica</h2>
            <p class="mt-1 text-gray-600">
                <?= htmlspecialchars($paciente['nombre'], ENT_QUOTES, 'UTF-8') ?>
                · DNI <?= htmlspecialchars($paciente['dni'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
        <a href="index.php"
           class="shrink-0 rounded-lg border border-gray-300 px-4 py-2 text-gray-600 transition hover:bg-gray-50">
            Volver a pacientes
        </a>
    </div>

    <section class="min-h-64 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">
        <h3 class="mb-4 text-base font-semibold text-gray-800">Registro clínico</h3>
        <?php if (empty($paciente['historia_clinica'])): ?>
            <p class="text-gray-500">Este paciente todavía no tiene entradas en su historia clínica.</p>
        <?php else: ?>
            <div class="whitespace-pre-wrap break-words leading-relaxed text-gray-700"><?= htmlspecialchars($paciente['historia_clinica'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </section>
</main>
