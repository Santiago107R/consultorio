<?php require_once '../config/parameters.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorio</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js" defer></script>
</head>

<body class="bg-[url('<?= base_url ?>assets/img/arg-salud-2.jpg')] bg-cover bg-center bg-no-repeat">
    <div class="flex justify-center items-center min-h-screen w-full bg-black/50 px-4">

        <div class="flex flex-col justify-center items-center w-full max-w-sm rounded-xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg text-white p-8">
            <h2 class="text-xl mb-4 font-semibold">Iniciar Sesión</h2>
            <p id="mensaje" role="status"></p>
            <div class="w-full">
                <form id="formLogin" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-sm">Ingrese su email</label>
                        <input type="email" name="email" id="email" class="px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="contrasena" class="text-sm">Ingrese su contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="px-3 py-1.5 rounded-md text-sm text-black outline-none focus:ring-2 focus:ring-white/50">
                    </div>

                    <button type="submit" id="btnSubmit" class="mt-2 py-2 bg-[#2196F3]/30 hover:bg-[#2196F3]/40 transition-colors rounded-md text-sm font-semibold border border-[#2196F3]/10">
                        Entrar
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        const form = document.getElementById('formLogin');
        const mensaje = document.getElementById('mensaje');
        const btnSubmit = document.getElementById('btnSubmit');

        form.addEventListener('submit', async (evento) => {
            evento.preventDefault();

            mensaje.textContent = '';
            mensaje.className = '';
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Ingresando…';

            const datos = new FormData(form);

            try {
                const respuesta = await fetch('../api/loginApi.php', {
                    method: 'POST',
                    body: datos
                });

                const resultado = await respuesta.json();

                if (resultado.ok) {
                    mensaje.textContent = resultado.mensaje || 'Login correcto.';
                    mensaje.className = 'text-green-500';
                    window.location.href = '<?= base_url ?>';
                } else {
                    mensaje.textContent = resultado.mensaje || 'No se pudo iniciar sesión.';
                    mensaje.className = 'text-red-500';
                }
            } catch (error) {
                mensaje.textContent = 'No se pudo conectar con el servidor.';
                mensaje.className = 'text-red-500';
                console.error(error);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Iniciar sesión';
            }
        });
    </script>
</body>

</html>