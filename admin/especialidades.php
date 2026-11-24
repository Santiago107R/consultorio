<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoEspecialidades = $conexion->query(
    'SELECT id, nombre FROM especialidades ORDER BY id ASC'
);
?>
<?php require_once './layouts/header.php' ?>
<main class="w-4/5 mx-auto grid grid-cols-1 gap-5 mt-2 p-5">

    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Especialidades</h2>
        <a href="crearSecretario.php">
            <button class="bg-[#2196F3] font-semibold rounded-lg cursor-pointer text-white px-2">Crear</button>
        </a>
    </div>

    <?php if (!empty($_GET['error'])): ?>
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
            <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

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
                <?php $idEsp = (int) $esp['id']; ?>
                <tr class="grid grid-flow-col auto-cols-fr hover:bg-gray-50 transition-colors items-center">
                    <td class="p-3 text-gray-700 font-medium"><?= htmlspecialchars($esp['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700">
                        <div class="relative flex items-center justify-center">
                            <button onclick="toggleMenu(event, 'menu-esp-<?= $indiceEsp ?>')" class="cursor-pointer p-1 rounded-full hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none"></ion-icon>
                            </button>
                            <div id="menu-esp-<?= $indiceEsp ?>" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-32 py-1">
                                <ul class="flex flex-col text-left">
                                    <li>
                                        <a href="editarEspecialidad.php?id=<?= $idEsp ?>" class="block px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button"
                                                onclick="abrirModalEliminarEsp(<?= $idEsp ?>, '<?= addslashes($esp['nombre'] ?? 'esta especialidad') ?>')"
                                                class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer text-sm">
                                            Eliminar
                                        </button>
                                    </li>
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

<div id="modal-eliminar-esp" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-sm rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="text-base font-semibold text-gray-800">Eliminar especialidad</h3>
        <p class="mt-2 text-xs text-gray-500">
            ¿Seguro que querés eliminar <span id="nombre-esp-eliminar" class="font-semibold text-gray-800"></span>?
        </p>

        <form action="eliminarEspecialidad.php" method="POST" class="mt-5 flex justify-end gap-2 text-sm">
            <input type="hidden" name="id" id="id-esp-eliminar">
            <button type="button" onclick="cerrarModalEliminarEsp()"
                    class="cursor-pointer rounded-lg border border-gray-300 px-4 py-1.5 text-gray-600 hover:bg-gray-50">
                Cancelar
            </button>
            <button type="submit"
                    class="cursor-pointer rounded-lg bg-red-600 px-4 py-1.5 font-semibold text-white hover:bg-red-700">
                Eliminar
            </button>
        </form>
    </div>
</div>

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

    function abrirModalEliminarEsp(id, nombre) {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
        document.getElementById('id-esp-eliminar').value = id;
        document.getElementById('nombre-esp-eliminar').textContent = nombre;
        const modal = document.getElementById('modal-eliminar-esp');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function cerrarModalEliminarEsp() {
        const modal = document.getElementById('modal-eliminar-esp');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
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