<?php require_once './layouts/header.php'; ?>

<div>
    <p id="mensaje"></p>
    <ul id="lista"></ul>
</div>

<script>
    async function getDoctores() {
        const mensaje = document.getElementById('mensaje');
        const lista = document.getElementById('lista');

        try {
            const respuesta = await fetch('../api/doctoresApi.php', {
                method: 'GET'
            });

            const resultado = await respuesta.json();

            if (resultado.ok) {
                mensaje.textContent = resultado.mensaje || 'ok.';
                mensaje.className = 'text-green-500';
                
                lista.innerHTML = '';

                resultado.doctores.forEach(doctor => {
                    const li = document.createElement('li');
                    li.textContent = `ID: ${doctor.id} - Usuario ID: ${doctor.usuario_id}`;
                    lista.appendChild(li);
                });

                console.log(resultado);
            } else {
                mensaje.textContent = resultado.mensaje || 'error.';
                mensaje.className = 'text-red-500';
            }
        } catch (error) {
            mensaje.textContent = 'No se pudo conectar con el servidor.';
            mensaje.className = 'text-red-500';
            console.error(error);
        }
    }
    
    getDoctores();
</script>
</body>
</html>