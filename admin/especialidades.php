<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php'; ?>
<?php require_once './layouts/header.php'; ?>
    

<main class="w-4/5 mx-auto grid grid-cols-1 lg:grid-cols-[68%_30%] gap-6 mt-4 p-5">

    <!-- SECCIÓN IZQUIERDA: Grid de Tarjetas de Especialidades -->
    <div class="space-y-4">
        <!-- Encabezado de Sección -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Especialidades Médicas</h2>
                <p class="text-xs text-gray-500">Gestión de áreas y departamentos del consultorio</p>
            </div>
            <button class="bg-[#2196F3] hover:bg-blue-600 font-semibold rounded-lg text-white text-sm px-4 py-2 transition-all duration-200 shadow-sm cursor-pointer flex items-center gap-1">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
                <span>Nueva Especialidad</span>
            </button>
        </div>

        <!-- Contenedor Scrollable de Tarjetas en 2 Columnas -->
        <div class="max-h-[750px] overflow-y-auto pr-1" id="tarjetas-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Tarjeta 1: Cardiología -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400">#1</span>
                            <!-- Botón de Acciones -->
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-1')" class="cursor-pointer p-1.5 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-1" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-36 py-1">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="people-outline"></ion-icon> Ver Detalles
                                        </li>
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer flex items-center gap-2 border-t border-gray-100">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2196F3] flex items-center justify-center text-xl font-bold">
                                <ion-icon name="heart-outline"></ion-icon>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-base">Cardiología</h3>
                                <p class="text-xs text-gray-500">Salud cardiovascular</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-2">
                        <span class="text-xs font-medium text-gray-600">3 Médicos activos</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#2196F3]">
                            Activo
                        </span>
                    </div>
                </div>

                <!-- Tarjeta 2: Pediatría -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400">#2</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-2')" class="cursor-pointer p-1.5 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-2" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-36 py-1">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="people-outline"></ion-icon> Ver Detalles
                                        </li>
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer flex items-center gap-2 border-t border-gray-100">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                                <ion-icon name="happy-outline"></ion-icon>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-base">Pediatría</h3>
                                <p class="text-xs text-gray-500">Atención infantil</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-2">
                        <span class="text-xs font-medium text-gray-600">5 Médicos activos</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#2196F3]">
                            Activo
                        </span>
                    </div>
                </div>

                <!-- Tarjeta 3: Traumatología -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400">#3</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-3')" class="cursor-pointer p-1.5 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-3" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-36 py-1">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="people-outline"></ion-icon> Ver Detalles
                                        </li>
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer flex items-center gap-2 border-t border-gray-100">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                                <ion-icon name="body-outline"></ion-icon>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-base">Traumatología</h3>
                                <p class="text-xs text-gray-500">Huesos y articulaciones</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-2">
                        <span class="text-xs font-medium text-gray-600">2 Médicos activos</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#2196F3]">
                            Activo
                        </span>
                    </div>
                </div>

                <!-- Tarjeta 4: Dermatología -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400">#4</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-4')" class="cursor-pointer p-1.5 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-4" class="action-menu hidden fixed z-50 bg-white rounded-md shadow-lg border border-gray-200 w-36 py-1">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="people-outline"></ion-icon> Ver Detalles
                                        </li>
                                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-700 flex items-center gap-2">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="px-4 py-2 hover:bg-red-50 text-red-600 cursor-pointer flex items-center gap-2 border-t border-gray-100">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                                <ion-icon name="sparkles-outline"></ion-icon>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-base">Dermatología</h3>
                                <p class="text-xs text-gray-500">Cuidado de la piel</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-2">
                        <span class="text-xs font-medium text-gray-600">4 Médicos activos</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#2196F3]">
                            Activo
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECCIÓN LATERAL: Detalles de la Especialidad Seleccionada -->
    <div class="space-y-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detalles de Especialidad</h2>
            <p class="text-xs text-gray-500">Información del área seleccionada</p>
        </div>

        <div class="flex flex-col max-h-[750px] overflow-hidden rounded-xl border border-gray-200 bg-white text-sm shadow-sm">
            <div class="bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200 p-3.5 flex justify-between items-center">
                <span>Cardiología</span>
                <span class="text-[10px] bg-blue-100 text-[#0D47A1] px-2 py-0.5 rounded uppercase font-semibold">Seleccionado</span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Médicos Asignados</h3>
                    <div class="space-y-2">
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50 hover:bg-white hover:border-gray-200 transition-all">
                            <p class="font-semibold text-gray-800 text-xs">Dr. Roberto Gómez</p>
                            <span class="text-[11px] text-gray-500">Matrícula: MN-84920</span>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50 hover:bg-white hover:border-gray-200 transition-all">
                            <p class="font-semibold text-gray-800 text-xs">Dra. María Fernández</p>
                            <span class="text-[11px] text-gray-500">Matrícula: MN-10294</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Servicios Incluidos</h3>
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-lg bg-gray-50 text-gray-700">
                            <span>Electrocardiograma</span>
                            <ion-icon name="checkmark-circle-outline" class="text-emerald-500 text-base"></ion-icon>
                        </div>
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-lg bg-gray-50 text-gray-700">
                            <span>Ecocardiograma</span>
                            <ion-icon name="checkmark-circle-outline" class="text-emerald-500 text-base"></ion-icon>
                        </div>
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-lg bg-gray-50 text-gray-700">
                            <span>Ergometría</span>
                            <ion-icon name="checkmark-circle-outline" class="text-emerald-500 text-base"></ion-icon>
                        </div>
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

    const tarjetasContainer = document.getElementById('tarjetas-container');
    if (tarjetasContainer) {
        tarjetasContainer.addEventListener('scroll', () => {
            document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
        });
    }
</script>
</body>
</html>