<?php
    require_once './layouts/header.php'; 
    require_once __DIR__ . '/../config/conexion.php';

    $queryEspecialidades = "SELECT id, nombre, 'especialidad' AS tipo FROM especialidades";
    $queryServicios = "SELECT id, nombre, 'servicio' AS tipo FROM servicios";

    $resultadosEspecialidades = $conexion->query($queryEspecialidades)->fetch_all(MYSQLI_ASSOC);
    $resultadosServicios = $conexion->query($queryServicios)->fetch_all(MYSQLI_ASSOC);

    $practicas = array_merge($resultadosEspecialidades, $resultadosServicios);
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Sacar nuevo turno</h2>
        
        <form action="elegirProfesional.php" method="GET" class="w-[40%] h-64 flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">
            
            <div class="flex flex-col w-full text-center space-y-2">
                <label for="practica_nombre" class="text-gray-800">Busca la práctica que necesitas</label>
                
                <div class="relative w-full">
                    <input 
                    type="search"
                    name="practica_nombre"
                    id="practica_nombre"
                    autocomplete="off" 
                    class="w-full border border-border rounded-md px-2 py-1"
                    required
                    >
                    
                    <input type="hidden" name="practica_id" id="practica_id" required>
                    <input type="hidden" name="tipo_practica" id="tipo_practica" required>
                    
                    <ul id="lista-sugerencias" class="absolute w-full bg-white border border-gray-200 mt-1 max-h-40 overflow-y-auto rounded-md shadow-lg hidden z-10">
                    </ul>
                </div>
            </div>

            <button type="submit" class="mt-auto self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Continuar
            </button>
        </form>
    </div>
</main>
<script>
    const practicas = <?php echo json_encode($practicas); ?>;
    
    const inputNombre = document.getElementById('practica_nombre');
    const inputId = document.getElementById('practica_id'); 
    const inputTipo = document.getElementById('tipo_practica'); 
    const listaSugerencias = document.getElementById('lista-sugerencias');

    inputNombre.addEventListener('input', function() {
        const valor = this.value.toLowerCase().trim();
        listaSugerencias.innerHTML = ''; 
        
        inputId.value = '';

        if (!valor) {
            listaSugerencias.classList.add('hidden');
            return;
        }

        const filtrados = practicas.filter(practica => 
            practica.nombre.toLowerCase().includes(valor)
        );

        if (filtrados.length > 0) {
            listaSugerencias.classList.remove('hidden');
            
            filtrados.forEach(practica => {
                const li = document.createElement('li');
                li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 text-left text-gray-700';
                li.textContent = practica.nombre;
                
                li.addEventListener('click', () => {
                    inputNombre.value = practica.nombre;
                    inputId.value = practica.id; 
                    inputTipo.value = practica.tipo;
                    
                    listaSugerencias.classList.add('hidden');
                });
                
                listaSugerencias.appendChild(li);
            });
        }
    });

    document.addEventListener('click', (e) => {
        if (e.target !== inputNombre && e.target !== listaSugerencias) {
            listaSugerencias.classList.add('hidden');
        }
    });
</script>