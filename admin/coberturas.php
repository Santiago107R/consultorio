<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoCoberturas = $conexion->query(
    'SELECT cobertura.id, cobertura.nombre, COUNT(plan.id) AS total_planes
    FROM coberturas_medica AS cobertura
    LEFT JOIN planes AS plan ON plan.cobertura_id = cobertura.id
    GROUP BY cobertura.id, cobertura.nombre
    ORDER BY cobertura.id ASC'
);

$coberturaSolicitada = filter_var(
    $_GET['cobertura_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$primeraCoberturaId = null;
$primerNombreCobertura = null;
$coberturaSeleccionadaId = null;
$nombreCoberturaSeleccionada = null;

while ($cobertura = $resultadoCoberturas->fetch_assoc()) {
    if ($primeraCoberturaId === null) {
        $primeraCoberturaId = (int) $cobertura['id'];
        $primerNombreCobertura = $cobertura['nombre'];
    }

    if ($coberturaSolicitada !== false && $coberturaSolicitada !== null
        && (int) $cobertura['id'] === $coberturaSolicitada) {
        $coberturaSeleccionadaId = (int) $cobertura['id'];
        $nombreCoberturaSeleccionada = $cobertura['nombre'];
    }
}

if ($coberturaSeleccionadaId === null) {
    $coberturaSeleccionadaId = $primeraCoberturaId;
    $nombreCoberturaSeleccionada = $primerNombreCobertura;
}

$resultadoCoberturas->data_seek(0);
$resultadoPlanes = null;

if ($coberturaSeleccionadaId !== null) {
    $consultaPlanes = $conexion->prepare(
        'SELECT nombre FROM planes WHERE cobertura_id = ? ORDER BY id ASC'
    );
    $consultaPlanes->bind_param('i', $coberturaSeleccionadaId);
    $consultaPlanes->execute();
    $resultadoPlanes = $consultaPlanes->get_result();
}
?>
<?php require_once './layouts/header.php'; ?>

<main class="w-4/5 mx-auto grid grid-cols-1 lg:grid-cols-[68%_30%] gap-6 mt-4 p-5">

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-md font-bold text-gray-800">Coberturas Médicas</h2>
                <p class="text-xs text-gray-500">Obras sociales y prepagas vinculadas al consultorio</p>
            </div>
            <button class="bg-[#0D47A1] hover:bg-[#1976D2] font-semibold rounded-lg text-white text-sm px-4 py-2 transition-all duration-200 shadow-sm cursor-pointer flex items-center gap-1">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
                <span>Nueva Cobertura</span>
            </button>
        </div>

        <!-- Contenedor de la Tabla -->
        <div class="max-h-[800px] overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
                        <th class="py-3.5 px-4 font-bold">#</th>
                        <th class="py-3.5 px-4 font-bold">Cobertura</th>
                        <th class="py-3.5 px-4 font-bold">Planes Asociados</th>
                        <th class="py-3.5 px-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php $indiceCobertura = 0; ?>
                    <?php while ($cobertura = $resultadoCoberturas->fetch_assoc()): ?>
                        <?php $indiceCobertura++; ?>
                        <tr class="cursor-pointer hover:bg-gray-50 transition-colors <?= (int) $cobertura['id'] === $coberturaSeleccionadaId ? 'bg-blue-50' : '' ?>">
                            <td class="py-3.5 px-4 font-semibold text-xs text-gray-500">#<?= $indiceCobertura ?></td>
                            <td class="py-3.5 px-4 font-bold text-gray-800">
                                <a href="?cobertura_id=<?= (int) $cobertura['id'] ?>" class="block">
                                    <?= htmlspecialchars($cobertura['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-gray-500">
                                <?= (int) $cobertura['total_planes'] ?> plan(es) asociado(s)
                            </td>
                            <td class="py-3.5 px-4 text-center relative">
                                <button onclick="toggleMenu(event, 'menu-cob-<?= (int) $cobertura['id'] ?>')" class="cursor-pointer rounded-full p-1.5 text-gray-500 transition-colors hover:bg-gray-200">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-cob-<?= (int) $cobertura['id'] ?>" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg text-left">
                                    <ul class="flex flex-col text-xs font-medium">
                                        <li>
                                            <a href="?cobertura_id=<?= (int) $cobertura['id'] ?>" class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                                <ion-icon name="card-outline"></ion-icon> Ver Planes
                                            </a>
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 text-red-600 border-t border-gray-100 px-4 py-2 hover:bg-red-50">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <h2 class="text-md font-bold text-gray-800">Planes Disponibles</h2>
            <p class="text-xs text-gray-500">Detalle de planes de la cobertura elegida</p>
        </div>

        <div class="flex flex-col max-h-[800px] overflow-hidden rounded-xl border border-gray-200 bg-white text-sm shadow-sm">
            <div class="bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200 p-3.5 flex justify-between items-center">
                <span><?= $nombreCoberturaSeleccionada !== null
                    ? htmlspecialchars($nombreCoberturaSeleccionada, ENT_QUOTES, 'UTF-8')
                    : 'Seleccione una cobertura' ?></span>
                <span class="text-[10px] bg-blue-100 text-[#0D47A1] px-2 py-0.5 rounded uppercase font-semibold">Seleccionado</span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Planes Activos</h3>
                    <div class="space-y-2">
                        <?php if ($resultadoPlanes === null || $resultadoPlanes->num_rows === 0): ?>
                            <p class="text-xs text-gray-400 italic">No hay planes cargados para esta cobertura.</p>
                        <?php else: ?>
                            <?php while ($plan = $resultadoPlanes->fetch_assoc()): ?>
                                <div class="p-3 rounded-lg border border-gray-100 bg-gray-50 hover:bg-white hover:border-gray-200 transition-all flex justify-between items-center">
                                    <p class="font-semibold text-gray-800 text-xs"><?= htmlspecialchars($plan['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-semibold">Activo</span>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    function toggleMenu(event, menuId) {
        event.stopPropagation();
        const menu = document.getElementById(menuId);
        const isCurrentlyHidden = menu.classList.contains('hidden');

        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));

        if (isCurrentlyHidden) {
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();

            menu.style.top = `${rect.top + (rect.height / 2) - 16}px`;
            menu.style.left = `${rect.left - 140}px`;

            menu.classList.remove('hidden');
        }
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });
</script>
</body>
</html>