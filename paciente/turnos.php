<?php require_once './layouts/header.php' ?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 font-medium">
    
    <div class="w-full flex items-end justify-between">
        
        <div class="max-w-3xl">
            <h2 class="mb-2 text-2xl font-semibold text-black">
                Hola, 
                <?= htmlspecialchars(
                    $_SESSION['usuario']['nombre'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>
            
            <p class="text-base font-medium leading-relaxed text-gray-700">
                Gestioná tus citas programadas, consultá los requerimientos previos y descargá tus 
                informes diagnósticos certificados en formato digital.
            </p>
        </div>

        <button class="flex items-center justify-center px-4 py-2 text-sm font-medium transition-colors rounded-md bg-(--color-paciente-primary) text-(--color-paciente-white) hover:opacity-90 whitespace-nowrap">
            <span class="flex items-center justify-center gap-2">
                <ion-icon class="size-5" name="add-circle-outline"></ion-icon> 
                Solicitar Nuevo Turno
            </span>
        </button>
    </div>

    <div class="w-full flex items-end justify-between gap-4">
        <div class="w-full flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">PRÓXIMO TURNO</span>
                <ion-icon name="today-outline" class="text-sm text-(--color-paciente-primary) bg-sky-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl text-(--color-paciente-primary) font-medium leading-relaxed">Jueves 24 Oct.</h2>  
                <p class="text-sm text-gray-800 font-medium leading-loose">10:30hs - Cardiología</p>
                <p class="text-xs">Dr. Martín Fernandez</p>
            </div>
        </div>

        <div class="w-full flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">TURNOS HISTÓRICOS</span>
                <ion-icon name="checkmark-done-outline" class="text-sm text-(--color-paciente-badge-text) bg-sky-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl text-gray-800 font-medium leading-relaxed">18 Atenciones</h2>  
                <p class="text-sm text-gray-800 font-medium leading-loose">Última: 18 de Octubre (Laboratorio)</p>
                <p class="text-xs">Registros clínicos desde 2021</p>
            </div>
        </div>

        <div class="w-full flex flex-col bg-(--color-paciente-white) border border-neutral-100 rounded-lg p-4 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium">ESTUDIOS CON INFORME DIGITAL</span>
                <ion-icon name="document-text-outline" class="text-sm text-gray-800 bg-teal-100 p-1.5 rounded-md"></ion-icon>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl text-(--color-paciente-primary) font-medium leading-relaxed">2 Disponibles <span class="text-xs">en pdf</span></h2>  
                <p class="text-sm text-gray-800 font-medium leading-loose">10:30hs - Cardiología</p>
                <p class="text-xs">Firmados digitalmente y listos</p>
            </div>
        </div>
    </div>

    <div class="w-full flex items-center justify-between text-gray-800 text-sm">
        <div class="flex flex-row gap-2 bg-slate-200 rounded-lg p-1">
                <button id="proximos-turnos" class="hover:bg-(--color-paciente-white) hover:text-(--color-paciente-primary) rounded-md p-2">
                    <span class="flex items-center gap-2">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Próximos Turnos
                        <!-- (2) -->
                    </span>
                </button>
                <button id="historial-turnos" class="hover:bg-(--color-paciente-white) hover:text-(--color-paciente-primary) rounded-md p-2">
                    <span class="flex items-center gap-2">
                        <ion-icon name="newspaper-outline"></ion-icon>
                        Historial Completo
                        <!-- (3) -->
                    </span>
                </button>
        </div>
        
        <div id="filtrar-proximos" class="flex flex-row items-center justify-center gap-2 text-gray-700">
            <span class="uppercase">filtrar:</span>
            <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                Todos los tipos
            </button>
            <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                Consultas médicas
            </button>
            <button class="bg-(--color-paciente-white) hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white) rounded-lg py-0.5 px-2">
                Servicios
            </button>
        </div>
    </div>

    <div class="w-full h-96 grid grid-cols-1 gap-4 overflow-y-auto p-4 text-sm text-gray-800 border border-slate-200 rounded-lg scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-100 hover:scrollbar-thumb-slate-400 scrollbar-thumb-rounded-full">

        <div class="bg-white flex flex-col justify-between gap-6 p-6 shadow rounded-lg">
            <div class="flex flex-row items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 leading-relaxed">
                        <span class="bg-slate-100 rounded-md text-xs py-0.5 px-2">Consulta Médica de Especialidad</span>
                        <span class="rounded-md py-0.5 px-2 text-xs font-medium <?= badge_status_classes['pendiente'] ?>">Pendiente</span>
                    </div>
                    <div>
                        <div>
                            <h2 class="text-xl leading-relaxed">Cardiología</h2>
                            <span class="text-base">Dr. Martín Fernandez</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-base text-(--color-paciente-primary)">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                    <span>Jueves 24 de Octubre • 10:30 hs</span>
                </div>
            </div>
            <div class="flex items-center justify-end">
                <div class="flex flex-row gap-4">
                    <button id="detalle-turno" class="flex items-center gap-2 bg-slate-200 hover:bg-slate-300 rounded-md p-2">
                        <ion-icon name="eye-outline"></ion-icon>
                        <span>Ver Detalle</span>
                    </button>
                    <button id="reprogramar-turno" class="flex items-center gap-2 bg-slate-200 hover:bg-slate-300 rounded-md p-2">
                        <ion-icon name="reload-outline"></ion-icon>
                        <span>Reprogramar</span>
                    </button>
                    <button id="cancelar-turno" class="flex items-center gap-2 text-red-500 hover:bg-red-50 rounded-md p-2">
                        <ion-icon name="close-circle-outline"></ion-icon>
                        <span>Cancelar Turno</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 shadow rounded-lg">
            Card 2
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 shadow rounded-lg">
            Card 3
        </div>
        
    </div>

</main>