<?php
 require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
 session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Argentina Salud</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnCerrarSession = document.getElementById('cerrar-session-btn');

            btnCerrarSession?.addEventListener('click', async () => {
                try {
                    const respuesta = await fetch('<?= api_url ?>destroySession.php', {
                        method: 'POST',
                        credentials: 'same-origin'
                    });

                    if (!respuesta.ok) {
                        throw new Error('No se pudo cerrar la sesión.');
                    }

                    window.location.replace('<?= base_url ?>');
                } catch (error) {
                    console.error(error);
                }
            });
        });
    </script>
    <link rel="stylesheet" href="<?= base_url ?>assets/css/global.css">
</head>

<body class="min-h-screen bg-(--color-paciente-background)">
    <header class="flex flex-row h-15 items-center justify-between p-3 px-10 bg-(--color-paciente-white) text-(--color-paciente-neutral) font-semibold">
        <div class="flex flex-row items-center gap-10">
            <div class="flex flex-col">
                <h2 class="text-xl text-(--color-paciente-primary) font-semibold leading-none">
                    Argentina Salud
                </h2>
                <span class="text-[10px] leading-none mt-1">
                    PORTAL DEL PACIENTE
                </span>
            </div>     

            <nav>
                <ul class="flex flex-row gap-2">
                    <li class="list-none py-1 px-2 rounded-md text-sm hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white)"><a href="<?= base_url ?>">Mis Turnos</a></li>
                    <li class="list-none py-1 px-2 rounded-md text-sm hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white)"><a href="<?= base_url ?>admin/doctores.php">Nuevo Turno</a></li>
                    <li class="list-none py-1 px-2 rounded-md text-sm hover:bg-(--color-paciente-primary) hover:text-(--color-paciente-white)"><a href="<?= base_url ?>admin/especialidades.php">Mi Perfil</a></li>
                    </ul>
            </nav>
        </div>    

       <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <?= htmlspecialchars(
                    $_SESSION['usuario']['nombre'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
                <ion-icon name="person-circle-outline" class="size-6"></ion-icon>
            </div>

            <button 
                id="cerrar-session-btn"
                class="flex items-center justify-center p-1 cursor-pointer"
                title="Cerrar sesión"
            >
                <ion-icon name="log-out-outline" class="size-6"></ion-icon>
            </button>
        </div>
    </header>