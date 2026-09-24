<?php require_once './layouts/header.php' ?>

<main class="w-4/5 mx-auto grid grid-cols-[80%_20%] gap-5 mt-2 p-5">

    <div class="flex justify-between">
        <h2>Pacientes</h2>
        <button class="bg-[#2196F3] font-semibold rounded-lg cursor-pointer text-white px-2">Crear</button>
    </div>

    <h2>Turnos</h2>

    <table class="grid grid-rows-[auto_1fr] max-h-[800px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md text-center">
        <thead class="grid bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200">
            <tr class="grid grid-flow-col auto-cols-fr">
                <th class="p-3">Nombre</th>
                <th class="p-3">Mail</th>
                <th class="p-3">Especialidad</th>
                <th class="p-3 whitespace-nowrap">Agenda fija</th>
                <th class="p-3 whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-body" class="grid overflow-y-auto divide-y divide-gray-100">

            <tr class="grid grid-flow-col auto-cols-fr hover:bg-gray-50 transition-colors">
                <td class="p-3 text-gray-700">Fernacho</td>
                <td class="p-3 text-gray-700">Fernacho@gmail.com</td>
                <td class="p-3 text-gray-700">Pediatría</td>
                <td class="p-3 text-gray-700"><button>Ver agenda</button></td>
                <td class="p-3 text-gray-700">
                    <div class="relative flex items-center justify-center">

                        <button onclick="toggleMenu(event, 'menu-1')" class="cursor-pointer p-1 rounded-full hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none"></ion-icon>
                        </button>

                        <div id="menu-1" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-32 py-1">
                            <ul class="flex flex-col text-left">
                                <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Turnos</li>
                                <li class="list-none px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Editar</li>
                                <li class="list-none px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer text-sm">Eliminar</li>
                            </ul>
                        </div>

                    </div>
                </td>
            </tr>

        </tbody>
    </table>

    <div class="flex flex-col max-h-[800px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md">
        <h2 class="bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200 p-3">Turnos</h2>
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            <p>turno 1</p><p>Paciente</p>
            <p>Fecha</p><p>estado</p>
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
            menu.style.left = `${rect.left - 135}px`;

            menu.classList.remove('hidden');
        }
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });

    document.getElementById('tabla-body').addEventListener('scroll', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });
</script>
</body>