<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php'; ?>
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
                <tbody id="tabla-coberturas-body" class="divide-y divide-gray-100 text-gray-700">
                    <!-- Se renderiza dinámicamente desde JavaScript -->
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
                <span id="detalle-titulo-cobertura">Seleccione una cobertura</span>
                <span class="text-[10px] bg-blue-100 text-[#0D47A1] px-2 py-0.5 rounded uppercase font-semibold">Seleccionado</span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Planes Activos</h3>
                    <div id="lista-planes-container" class="space-y-2">
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    let coberturasData = [];

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

    async function getCoberturas() {
        try {
            const respuesta = await fetch('<?= api_url ?>coberturasApi.php');
            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.ok) {
                throw new Error(resultado.mensaje || 'No se pudieron cargar las coberturas.');
            }

            return resultado.coberturas;
        } catch (error) {
            console.error('Error al cargar coberturas:', error);
        }
    }

    function seleccionarCobertura(index) {
        const cobertura = coberturasData[index];
        if (!cobertura) return;

        document.getElementById('detalle-titulo-cobertura').innerText = cobertura.nombre;

        const planesContainer = document.getElementById('lista-planes-container');
        planesContainer.innerHTML = '';

        if (!cobertura.planes || cobertura.planes.length === 0) {
            planesContainer.innerHTML = `<p class="text-xs text-gray-400 italic">No hay planes cargados para esta cobertura.</p>`;
            return;
        }

        cobertura.planes.forEach(plan => {
            planesContainer.innerHTML += `
                <div class="p-3 rounded-lg border border-gray-100 bg-gray-50 hover:bg-white hover:border-gray-200 transition-all flex justify-between items-center">
                    <p class="font-semibold text-gray-800 text-xs">${plan.nombre}</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-semibold">Activo</span>
                </div>
            `;
        });
    }

    // Renderizado de la Tabla
    getCoberturas().then(coberturas => {
        if (!coberturas || coberturas.length === 0) return;

        coberturasData = coberturas;
        const tbody = document.getElementById('tabla-coberturas-body');
        tbody.innerHTML = '';

        coberturas.forEach((cob, index) => {
            const totalPlanes = cob.planes ? cob.planes.length : 0;

            tbody.innerHTML += `
                <tr onclick="seleccionarCobertura(${index})" class="cursor-pointer hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4 font-semibold text-xs text-gray-500">#${index + 1}</td>
                    <td class="py-3.5 px-4 font-bold text-gray-800">${cob.nombre}</td>
                    <td class="py-3.5 px-4 text-xs text-gray-500">${totalPlanes} plan(es) asociado(s)</td>
                    <td class="py-3.5 px-4 text-center relative">
                        <button onclick="toggleMenu(event, 'menu-cob-${index}')" class="cursor-pointer rounded-full p-1.5 text-gray-500 transition-colors hover:bg-gray-200">
                            <ion-icon name="ellipsis-vertical-outline" class="pointer-events-none text-base"></ion-icon>
                        </button>
                        <div id="menu-cob-${index}" class="action-menu fixed z-50 hidden w-36 rounded-md border border-gray-200 bg-white py-1 shadow-lg text-left">
                            <ul class="flex flex-col text-xs font-medium">
                                <li onclick="seleccionarCobertura(${index})" class="flex cursor-pointer items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                    <ion-icon name="card-outline"></ion-icon> Ver Planes
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
            `;
        });

        seleccionarCobertura(0);
    });
</script>
</body>
</html>