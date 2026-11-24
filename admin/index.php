<?php
require_once __DIR__ . '/layouts/auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoPacientes = $conexion->query(
    'SELECT paciente.id AS paciente_id, usuario.nombre AS nombre, paciente.dni AS dni
    FROM pacientes AS paciente
    INNER JOIN usuarios AS usuario ON usuario.id = paciente.usuario_id
    ORDER BY paciente.id ASC'
);
?>
<?php require_once './layouts/header.php' ?>
<main class="w-4/5 mx-auto grid grid-cols-1fr gap-5 mt-2 p-5">

    <div class="flex justify-between">
        <h2>Pacientes</h2>
    </div>

    <?php if (isset($_GET['actualizado'])): ?>
        <p class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700" role="status">
            Los datos del paciente se actualizaron correctamente.
        </p>
    <?php elseif (!empty($_GET['error'])): ?>
        <p class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600" role="alert">
            <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <table class="grid grid-rows-[auto_1fr] max-h-[800px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md text-center">
        <thead class="grid bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200">
            <tr class="grid grid-flow-col auto-cols-fr">
                <th class="p-3">nombre</th>
                <th class="p-3">dni</th>
                <th class="p-3 whitespace-nowrap">historia clínica</th>
                <th class="p-3 whitespace-nowrap">acciones</th>
            </tr>
        </thead>
        <tbody id="tabla_body" class="grid overflow-y-auto divide-y divide-gray-100">
            <?php while ($paciente = $resultadoPacientes->fetch_assoc()): ?>
                <tr class="grid grid-flow-col auto-cols-fr hover:bg-gray-50 transition-colors">
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($paciente['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($paciente['dni'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700">
                        <a href="historiaClinica.php?id=<?= (int) $paciente['paciente_id'] ?>"
                           class="text-(--color-paciente-primary) hover:underline">
                            Abrir
                        </a>
                    </td>
                    <td class="p-3 text-gray-700">
                        <a href="editarPaciente.php?id=<?= (int) $paciente['paciente_id'] ?>"
                           class="text-(--color-paciente-primary) hover:underline">
                            Editar
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</main>
</body>

</html>