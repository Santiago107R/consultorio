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

        <div class="max-h-[800px] overflow-y-auto pr-1" id="tarjetas-container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="tarjetas-grid">

                <!-- <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1]">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between">
                            <span class="text-xs font-semibold text-white/90">#1</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-serv-1')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-serv-1" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
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
                                <h3 class="text-base font-bold text-gray-800">Nombre del servicio</h3>
                            </div>
                        </div>
                    </div>
                </div> -->

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
    const tarjetasGrid = document.getElementById('tarjetas-grid');

    tarjetasContainer.addEventListener('scroll', () => {
        document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
    });


    async function getServicios() {
        try {
            const respuesta = await fetch('<?= api_url ?>serviciosApi.php');
            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.ok) {
                throw new Error(resultado.mensaje || 'No se pudieron cargar los servicios.');
            }

            return resultado.servicios;
        } catch (error) {
            console.error('Error al cargar servicios:', error);
        }
    }

    getServicios().then(servicios => {
        if (!servicios) return;

        tarjetasGrid.innerHTML = '';

        if (servicios.length === 0) {
            tarjetasGrid.innerHTML = '<p class="text-xs text-gray-500">Todavía no hay servicios cargados.</p>';
            return;
        }

        servicios.forEach(servicio => {

            tarjetasGrid.innerHTML += `
                <div class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-gray-200 bg-white p-2 pl-5 shadow-sm transition-all hover:shadow-md before:absolute before:left-0 before:right-0 before:top-0 before:h-13 before:bg-[#0D47A1]">
                    <div>
                        <div class="relative z-10 mb-6 flex items-center justify-between">
                            <span class="text-xs font-semibold text-white/90">#${servicio.id}</span>
                            <div class="relative">
                                <button onclick="toggleMenu(event, 'menu-serv-${servicio.id}')" class="cursor-pointer rounded-full p-1.5 text-white transition-colors hover:bg-white/20">
                                    <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                                </button>
                                <div id="menu-serv-${servicio.id}" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                    <ul class="flex flex-col text-left text-xs font-medium">
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
                                <h3 class="text-base font-bold text-gray-800">${servicio.nombre}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    });
</script>
</body>

</html>
