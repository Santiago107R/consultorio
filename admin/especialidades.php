<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php'; ?>
<?php require_once './layouts/header.php'; ?>
    

<main class="w-4/5 mx-auto grid grid-cols-1 lg:grid-cols-[68%_30%] gap-6 mt-4 p-5">

    <!-- SECCIÓN IZQUIERDA: Grid de Tarjetas de Especialidades -->
    <div class="space-y-4">
        <!-- Encabezado de Sección -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-md font-bold text-gray-800">Especialidades Médicas</h2>
                <p class="text-xs text-gray-500">Gestión de áreas y departamentos del consultorio</p>
            </div>
            <button class="bg-[#0D47A1] hover:bg-[#1976D2] font-semibold rounded-lg text-white text-sm px-4 py-2 transition-all duration-200 shadow-sm cursor-pointer flex items-center gap-1">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
                <span>Nueva Especialidad</span>
            </button>
        </div>

        <!-- Contenedor Scrollable de Tarjetas en 2 Columnas -->
        <div class="max-h-[800px] overflow-y-auto pr-1" id="tarjetas-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Tarjeta 1: Cardiología -->
                <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1] ">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between pb-">
                            <span class="text-xs font-semibold text-white/90">#1</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-1')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-1" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="people-outline"></ion-icon> Doctores
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 text-red-600 border-t border-gray-100 px-4 py-2 hover:bg-red-50">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 flex items-center gap-3">
                            <div class="pl-10">
                                <h3 class="text-base font-bold text-gray-800">Cardiología</h3>
                                <p class="text-xs text-gray-500">Salud cardiovascular</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 2: Pediatría -->
                <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1]  ">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between pb-">
                            <span class="text-xs font-semibold text-white/90">#2</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-1')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-1" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="people-outline"></ion-icon> Doctores
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 text-red-600 border-t border-gray-100 px-4 py-2 hover:bg-red-50">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 flex items-center gap-3">
                            <div class="pl-10">
                                <h3 class="font-bold text-gray-800 text-base">Pediatría</h3>
                                <p class="text-xs text-gray-500">Atención infantil</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 3: Traumatología -->
                <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1] ">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between pb-">
                            <span class="text-xs font-semibold text-white/90">#3</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-1')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-1" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="people-outline"></ion-icon> Doctores
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 text-red-600 border-t border-gray-100 px-4 py-2 hover:bg-red-50">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 flex items-center gap-3">
                            <div class="pl-10">
                                <h3 class="font-bold text-gray-800 text-base">Traumatología</h3>
                                <p class="text-xs text-gray-500">Huesos y articulaciones</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 4: Dermatología -->
                <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1] ">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between pb-">
                            <span class="text-xs font-semibold text-white/90">#4</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-esp-1')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-esp-1" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="people-outline"></ion-icon> Doctores
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                            <ion-icon name="create-outline"></ion-icon> Editar
                                        </li>
                                        <li class="flex cursor-pointer items-center gap-2 text-red-600 border-t border-gray-100 px-4 py-2 hover:bg-red-50">
                                            <ion-icon name="trash-outline"></ion-icon> Eliminar
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 flex items-center gap-3">
                            <div class="pl-10">
                                <h3 class="font-bold text-gray-800 text-base">Dermatología</h3>
                                <p class="text-xs text-gray-500">Cuidado de la piel</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECCIÓN LATERAL: Detalles de la Especialidad Seleccionada -->
    <div class="space-y-4">
        <div>
            <h2 class="text-md font-bold text-gray-800">Doctores</h2>
            <p class="text-xs text-gray-500">Información del área seleccionada</p>
        </div>

        <div class="flex flex-col max-h-[800px] overflow-hidden rounded-xl border border-gray-200 bg-white text-sm shadow-sm">
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