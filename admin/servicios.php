<?php
require_once __DIR__ . '/layouts/auth.php';
require_once __DIR__ . '/../config/conexion.php';

$resultadoServicios = $conexion->query(
    'SELECT id, nombre, dias_laborables, hora_inicio, hora_fin
     FROM servicios
     ORDER BY id ASC'
);
?>
<?php require_once './layouts/header.php'; ?>

<main class="w-4/5 mx-auto grid grid-cols-1 gap-6 mt-4 p-5">

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-md font-bold text-gray-800">Servicios</h2>
                <p class="text-xs text-gray-500">Gestión de los servicios que ofrece el consultorio</p>
            </div>
            <button class="bg-[#0D47A1] hover:bg-[#1976D2] font-semibold rounded-lg text-white text-sm px-4 py-2 transition-all duration-200 shadow-sm cursor-pointer flex items-center gap-1">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
                <span>Nuevo Servicio</span>
            </button>
        </div>

        <?php if (!empty($_GET['error'])): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php elseif (isset($_GET['eliminado'])): ?>
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700" role="status">
                El servicio se eliminó correctamente.
            </div>
        <?php elseif (isset($_GET['actualizado'])): ?>
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700" role="status">
                El servicio se actualizó correctamente.
            </div>
        <?php endif; ?>

        <div class="max-h-[800px] overflow-y-auto pr-1" id="tarjetas-container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if ($resultadoServicios->num_rows === 0): ?>
                    <p class="text-xs text-gray-500">Todavía no hay servicios cargados.</p>
                <?php else: ?>
                    <?php while ($servicio = $resultadoServicios->fetch_assoc()): ?>
                        <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1]">
                            <div>
                                <div class="relative z-10 mb-6 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-white/90">#<?= (int) $servicio['id'] ?></span>
                                    <div class="relative">
                                        <button onclick="toggleMenu(event, 'menu-serv-<?= (int) $servicio['id'] ?>')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                            <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                        </button>
                                        <div id="menu-serv-<?= (int) $servicio['id'] ?>" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                            <ul class="flex flex-col text-left text-xs font-medium">
                                                <li>
                                                    <a href="editarServicio.php?id=<?= (int) $servicio['id'] ?>"
                                                       class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                                        <ion-icon name="create-outline"></ion-icon> Editar
                                                    </a>
                                                </li>
                                                <li class="border-t border-gray-100">
                                                    <button type="button"
                                                            data-service-name="<?= htmlspecialchars($servicio['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                                            onclick="abrirModalEliminar(<?= (int) $servicio['id'] ?>, this.dataset.serviceName)"
                                                            class="flex w-full cursor-pointer items-center gap-2 px-4 py-2 text-left text-red-600 hover:bg-red-50">
                                                        <ion-icon name="trash-outline"></ion-icon> Eliminar
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 flex items-center gap-3">
                                    <div class="pl-10">
                                        <h3 class="text-base font-bold text-gray-800"><?= htmlspecialchars($servicio['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                                        <p class="mt-1 text-xs text-gray-500">
                                            <?= htmlspecialchars(substr($servicio['hora_inicio'], 0, 5), ENT_QUOTES, 'UTF-8') ?>
                                            – <?= htmlspecialchars(substr($servicio['hora_fin'], 0, 5), ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

<div id="modal-eliminar-servicio" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-sm rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="text-base font-semibold text-gray-800">Eliminar servicio</h3>
        <p class="mt-2 text-sm text-gray-500">
            ¿Seguro que querés eliminar <span id="nombre-servicio-eliminar" class="font-semibold text-gray-800"></span>?
            Los servicios con turnos actuales o históricos asociados no se pueden eliminar.
        </p>

        <form action="eliminarServicio.php" method="POST" class="mt-5 flex justify-end gap-2 text-sm">
            <input type="hidden" name="id" id="id-servicio-eliminar">
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
            menu.style.left = `${rect.left - 140}px`;

            menu.classList.remove('hidden');
        }
    }

    function abrirModalEliminar(id, nombre) {
        document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
        document.getElementById('id-servicio-eliminar').value = id;
        document.getElementById('nombre-servicio-eliminar').textContent = nombre;
        const modal = document.getElementById('modal-eliminar-servicio');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function cerrarModalEliminar() {
        const modal = document.getElementById('modal-eliminar-servicio');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });

    const tarjetasContainer = document.getElementById('tarjetas-container');
    tarjetasContainer.addEventListener('scroll', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });
</script>
</body>

</html>
