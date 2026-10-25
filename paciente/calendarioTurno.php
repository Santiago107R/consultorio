<?php
    require_once './layouts/header.php'; 
    require_once __DIR__ . '/../config/conexion.php';

    $agendas = [];

    if (!empty($_GET['practica_id']) && !empty($_GET['practica_nombre']) && !empty($_GET['tipo_practica'])) {
        $practica_id = $_GET['practica_id'];
        $practica_nombre = $_GET['practica_nombre'];
        $tipo_practica = $_GET['tipo_practica'];

        if ($tipo_practica == "servicio") {
            $query = "SELECT dias_laborables, hora_inicio FROM servicios WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('i', $practica_id);
            $stmt->execute(); 
            $resultado = $stmt->get_result();
            
            if ($fila = $resultado->fetch_assoc()) {
                $dias = explode(',', $fila['dias_laborables']);
                foreach($dias as $dia) {
                    $agendas[(int)$dia] = $fila['hora_inicio'];
                }
            }
            $stmt->close();

        } elseif ($tipo_practica == "especialidad" && !empty($_GET['doctor_id'])) {
            $doctor_id = $_GET['doctor_id'];
            $query = "SELECT dia, hora_inicio FROM agenda_fija WHERE doctor_id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('i', $doctor_id);
            $stmt->execute(); 
            $resultado = $stmt->get_result();
            
            while ($fila = $resultado->fetch_assoc()) {
                $agendas[(int)$fila['dia']] = $fila['hora_inicio'];
            }
            $stmt->close();
        }
    }
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Sacar nuevo turno</h2>
        
        <form action="confirmarTurno.php" method="POST" class="w-[40%] flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-6">
            
            <div class="flex flex-col w-full text-center space-y-4">
                <label class="text-gray-800 font-semibold">Elige una fecha para: <?= htmlspecialchars($practica_nombre); ?></label>
                
                <?php if (empty($agendas)): ?>
                    <div class="p-4 text-red-500 bg-red-50 rounded-lg border border-red-200">
                        <span>Este profesional/servicio no tiene horarios disponibles actualmente.</span>
                    </div>
                <?php else: ?>
                    
                    <div class="w-full flex justify-center">
                        <input type="date" name="fecha_turno" id="calendario" class="hidden" required>
                    </div>

                    <div id="contenedor-horarios" class="hidden w-full mt-2">
                        <div id="mensaje-hora" class="p-3 bg-blue-50 border border-blue-200 text-(--color-paciente-primary) rounded-md font-semibold">
                        </div>
                    </div>

                    <input type="time" name="hora_turno" id="hora_turno" required>

                <?php endif; ?>
            </div>

            <?php if(!empty($_GET['practica_id'])): ?>
                <input type="hidden" name="practica_id" value="<?= htmlspecialchars($_GET['practica_id']); ?>">
                <input type="hidden" name="practica_nombre" value="<?= htmlspecialchars($_GET['practica_nombre']); ?>">
                <input type="hidden" name="tipo_practica" value="<?= htmlspecialchars($_GET['tipo_practica']); ?>">
                <?php if(!empty($_GET['doctor_id'])): ?>
                    <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($_GET['doctor_id']); ?>">
                <?php endif; ?>
            <?php endif; ?>

            <button type="submit" id="btn-continuar" class="hidden mt-6 self-end bg-(--color-paciente-primary) text-white px-4 py-2 rounded-md cursor-pointer hover:bg-opacity-90 transition-all">
                Continuar
            </button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    const agendas = <?php echo json_encode($agendas); ?>;
    const diasPermitidos = Object.keys(agendas).map(Number);

    const contenedorHorarios = document.getElementById('contenedor-horarios');
    const mensajeHora = document.getElementById('mensaje-hora');
    const inputHora = document.getElementById('hora_turno');
    const btnContinuar = document.getElementById('btn-continuar');

    if (diasPermitidos.length > 0) {
        flatpickr("#calendario", {
            inline: true, 
            locale: "es", 
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return !diasPermitidos.includes(date.getDay());
                }
            ],
            onChange: function(selectedDates, dateStr, instance) {
                const diaDeSemana = selectedDates[0].getDay();
                const horaAsignada = agendas[diaDeSemana];

                if (horaAsignada) {
                    const horaLimpia = horaAsignada.substring(0, 5);
                    
                    mensajeHora.innerHTML = `Horario asignado: <b>${horaLimpia} hs</b>`;
                    
                    inputHora.value = horaAsignada; 
                    
                    contenedorHorarios.classList.remove('hidden');
                    btnContinuar.classList.remove('hidden');
                }
            }
        });
    }
</script>