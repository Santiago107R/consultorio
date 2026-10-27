<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoEspecialidades = $conexion->query(
    'SELECT id, nombre FROM especialidades ORDER BY id ASC'
);
?>
<?php require_once './layouts/header.php' ?>
<main class="w-4/5 mx-auto grid grid-cols-1fr gap-5 mt-2 p-5">

    <div class="flex justify-between">
        <h2>Especialidades</h2>
        <button class="bg-[#2196F3] font-semibold rounded-lg cursor-pointer text-white px-2">Crear</button>
    </div>

    <table class="grid grid-rows-[auto_1fr] max-h-[800px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md text-center">
        <thead class="grid bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200">
            <tr class="grid grid-flow-col auto-cols-fr">
                <th class="p-3">Nombre</th>
                <th class="p-3 whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla_body" class="grid overflow-y-auto divide-y divide-gray-100">
            <?php $indiceEsp = 0; ?>
            <?php while ($esp = $resultadoEspecialidades->fetch_assoc()): ?>
                <tr class="grid grid-flow-col auto-cols-fr hover:bg-gray-50 transition-colors items-center">
                    <td class="p-3 text-gray-700 font-medium"><?= htmlspecialchars($esp['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700">
                        <div class="relative flex items-center justify-center">
                            <button onclick="toggleMenu(event, 'menu-esp-<?= $indiceEsp ?>')" class="cursor-pointer p-1 rounded-full hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none"></ion-icon>
                            </button>
                            <div id="menu-esp-<?= $indiceEsp ?>" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-32 py-1">
                                <ul class="flex flex-col text-left">
                                    <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Editar</li>
                                    <li class="list-none px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer text-sm">Eliminar</li>
                                    <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Doctores</li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php $indiceEsp++; ?>
            <?php endwhile; ?>
        </tbody>
    </table>

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
            menu.style.left = `${rect.left - 135}px`;

            menu.classList.remove('hidden');
        }
    }

    const tabla_body = document.getElementById('tabla_body');

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });

    tabla_body.addEventListener('scroll', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });
</script>
</body>
</html>