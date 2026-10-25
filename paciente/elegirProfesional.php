<?php
    require_once './layouts/header.php'; 

    $resultadosDoctores = null;

    if (!empty($_GET['practica_id']) && !empty($_GET['practica_nombre']) && !empty($_GET['tipo_practica'])) {
        $practica_id = $_GET['practica_id'];
        $practica_nombre = $_GET['practica_nombre'];
        $tipo_practica = $_GET['tipo_practica'];

        if ($tipo_practica == "servicio") {
            header("Location: calendarioTurno.php?practica_nombre=$practica_nombre&practica_id=$practica_id&tipo_practica=$tipo_practica");
            exit();
        } 

        require_once __DIR__ . '/../config/conexion.php';

        $query = "SELECT doctor_id, u.nombre 
                  FROM doctor_especialidad 
                  INNER JOIN doctores ON doctores.id = doctor_id 
                  INNER JOIN usuarios AS u ON u.id = doctores.usuario_id  
                  WHERE especialidad_id = ?";
        
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('i', $practica_id);
        $stmt->execute(); 
        $resultadosDoctores = $stmt->get_result();
        $stmt->close();
    }
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Sacar nuevo turno</h2>
        
        <form action="calendarioTurno.php" method="GET" class="w-[40%] h-80 flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">
            
            <div class="flex flex-col w-full text-center space-y-2">
                <label class="text-gray-800">Elige un profesional para: <?= $practica_nombre ?></label>
                
                <div class="w-full h-52 overflow-y-auto flex flex-col justify-start gap-3 p-1">
                    <?php if (!$resultadosDoctores || $resultadosDoctores->num_rows === 0): ?>
                        <div class="p-4 text-gray-500 text-center bg-gray-50 rounded-lg border border-dashed">
                            <span>No se encontraron doctores para esta especialidad</span>
                        </div>
                    <?php else: ?>
                        <?php while ($doctor = $resultadosDoctores->fetch_assoc()): ?>
                            <label class="flex items-center space-x-4 p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-(--color-paciente-primary) cursor-pointer transition-all duration-200 w-full">

                                <input type="radio" name="doctor_id" value="<?php echo $doctor['doctor_id']; ?>" required class="w-5 h-5 text-(--color-paciente-primary) border-gray-300 focus:ring-(--color-paciente-primary) cursor-pointer">

                                <div class="flex flex-col text-left">
                                    <span class="font-semibold text-gray-800 text-base">
                                        <?php echo htmlspecialchars($doctor['nombre']); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 font-normal">
                                        Seleccionar profesional
                                    </span>
                                </div>

                            </label>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(!empty($_GET['practica_id'])): ?>
                <input type="hidden" name="practica_id" value="<?php echo htmlspecialchars($_GET['practica_id']); ?>">
                <input type="hidden" name="practica_nombre" value="<?php echo htmlspecialchars($_GET['practica_nombre']); ?>">
                <input type="hidden" name="tipo_practica" value="<?php echo htmlspecialchars($_GET['tipo_practica']); ?>">
            <?php endif; ?>

            <button type="submit" class="mt-auto self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Continuar
            </button>
        </form>
    </div>
</main>