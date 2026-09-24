<?php require_once './layouts/header.php'; ?>

<main>
	<h1>Pacientes</h1>

	<p id="mensaje"></p>
	<ul id="lista-pacientes"></ul>
</main>

<script>
	const listaPacientes = document.getElementById('lista-pacientes');
	const mensaje = document.getElementById('mensaje');

	fetch('../api/pacientesApi.php')
		.then((respuesta) => respuesta.json())
		.then((datos) => {
			if (!datos.ok) {
				throw new Error(datos.mensaje);
			}

			datos.pacientes.forEach((paciente) => {
				const elemento = document.createElement('li');
				elemento.textContent = `Paciente ${paciente.id} - Usuario ${paciente.id_usuario}`;
				listaPacientes.appendChild(elemento);
			});
		})
		.catch((error) => {
			mensaje.textContent = error.message || 'No se pudieron cargar los pacientes.';
		});
</script>

</body>
</html>
