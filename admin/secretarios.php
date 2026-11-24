<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoSecretarios = $conexion->query(
    "SELECT id, email, nombre FROM usuarios WHERE rol = 'secretario' ORDER BY id ASC"
);
?>
<?php require_once __DIR__ . '/layouts/header.php' ?>
<main class="w-4/5 mx-auto grid grid-cols-1fr gap-5 mt-2 p-5">

    <div class="flex justify-between">
        <h2>Secretarios</h2>
        <a href="crearSecretario.php">
            <span class="bg-[#2196F3] font-semibold rounded-lg cursor-pointer text-white px-2 py-1">Crear</span>
        </a>
    </div>

    <?php if (!empty($_GET['error'])): ?>
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600" role="alert">
            <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php elseif (isset($_GET['actualizado'])): ?>
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700" role="status">
            Los datos del secretario se actualizaron correctamente.
        </div>
    <?php elseif (isset($_GET['eliminado'])): ?>
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700" role="status">
            El secretario se eliminó correctamente.
        </div>
    <?php endif; ?>

    <table class="grid grid-rows-[auto_1fr] max-h-[800px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md text-center">
        <thead class="grid bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200">
            <tr class="grid grid-flow-col auto-cols-fr">
                <th class="p-3">email</th>
                <th class="p-3">nombre</th>
                <th class="p-3 whitespace-nowrap">acciones</th>
            </tr>
        </thead>
        <tbody id="tabla_body" class="grid overflow-y-auto divide-y divide-gray-100">
            <?php while ($secretario = $resultadoSecretarios->fetch_assoc()): ?>
                <tr class="grid grid-flow-col auto-cols-fr hover:bg-gray-50 transition-colors">
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($secretario['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($secretario['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="p-3 text-gray-700">
                        <div class="relative flex items-center justify-center">
                            <button onclick="toggleMenu(event, 'menu-sec-<?= (int) $secretario['id'] ?>')" class="cursor-pointer p-1 rounded-full hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none"></ion-icon>
                            </button>
                            <div id="menu-sec-<?= (int) $secretario['id'] ?>" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-32 py-1">
                                <ul class="flex flex-col text-left">
                                    <li>
                                        <a href="editarSecretario.php?id=<?= (int) $secretario['id'] ?>"
                                           class="block px-4 py-2 text-sm hover:bg-gray-100">
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button"
                                                data-secretary-name="<?= htmlspecialchars($secretario['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                                onclick="abrirModalEliminar(<?= (int) $secretario['id'] ?>, this.dataset.secretaryName)"
                                                class="w-full cursor-pointer px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                            Eliminar
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</main>

<div id="modal-eliminar-secretario" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-sm rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="text-base font-semibold text-gray-800">Eliminar secretario</h3>
        <p class="mt-2 text-sm text-gray-500">
            ¿Seguro que querés eliminar la cuenta de
            <span id="nombre-secretario-eliminar" class="font-semibold text-gray-800"></span>?
        </p>

        <form action="eliminarSecretario.php" method="POST" class="mt-5 flex justify-end gap-2 text-sm">
            <input type="hidden" name="id" id="id-secretario-eliminar">
            <button type="button" onclick="cerrarModalEliminar()"
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

    function abrirModalEliminar(id, nombre) {
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
        document.getElementById('id-secretario-eliminar').value = id;
        document.getElementById('nombre-secretario-eliminar').textContent = nombre;
        const modal = document.getElementById('modal-eliminar-secretario');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function cerrarModalEliminar() {
        const modal = document.getElementById('modal-eliminar-secretario');
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