<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php'; ?>
<!DOCTYPE html>
<html lang="es">

<!-- 0D47A1 -->
<!-- 2196F3 -->
<!-- 90CAF9 -->
<!-- E3F2FD -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorio</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js" defer></script>
</head>

<body class="min-h-screen">
    <header class="flex flex-row h-15 items-center justify-around p-3 bg-[#0D47A1] text-white text-md font-semibold">
        <!--/*este es un logo sencillo y de prueba para el header-->
        <!--<a href="<= base_url ?>" class="flex items-center gap-2.5 hover:opacity-95 transition-opacity">
            <svg class="w-8 h-8 rounded-lg shadow-sm" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="100" height="100" rx="20" fill="white"/>
                <path d="M72 32C65 24 53 22 41 27C27 33 20 48 22 63C25 78 40 88 56 85C66 83 74 76 78 68" stroke="#2196F3" stroke-width="8" stroke-linecap="round"/>
                <rect x="42" y="38" width="16" height="34" rx="4" fill="#0D47A1"/>
                <rect x="33" y="47" width="34" height="16" rx="4" fill="#0D47A1"/>
            </svg>
            <div class="flex flex-col justify-center">
                <span class="font-bold text-base leading-none tracking-tight text-white">CONSULTORIO</span>
                <span class="text-[10px] font-medium tracking-wider text-[#90CAF9] uppercase mt-0.5">Médico</span>
            </div>
        </a>-->

        <nav>
            <ul class="flex flex-row gap-5">
                <!-- <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="">Turnos</a></li> -->
                <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="<?= base_url ?>">Pacientes</a></li>
                <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="<?= base_url ?>admin/doctores.php">Doctores</a></li>
                <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="<?= base_url ?>admin/especialidades.php">Especialidades</a></li>
                <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="">Servicios</a></li>
                <li class="list-none p-1 rounded-md hover:bg-gray-400/40"><a href="">Coberturas</a></li>
            </ul>
        </nav>

        <button class="p-1 rounded-md hover:bg-gray-400/40"><a href="">Cerrar sesión</a></button>
    </header>