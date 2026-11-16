<?php
    require_once './layouts/header.php';

    $query = "SELECT e.id AS id, e.nombre AS nombre FROM doctor_especialidad AS de INNER JOIN especialidades AS e
                ON e.id = de.especialidad_id WHERE de.doctor_id = ?
    ";

    $idDoctor = $_SESSION['usuario']['doctor_id'];

    require_once __DIR__ . '/../config/conexion.php';

    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $idDoctor);
    $stmt->execute();
    $especialidades = $stmt->get_result();
    $stmt->close();
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Asignar un consultorio</h2>
        
        <form action="agendaDias.php" method="POST" class="w-[40%] h-64 flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">
            
            <div class="flex flex-col w-full text-center space-y-4">
                <h2 class="text-base text-gray-800">Seleccione una especialidad</h2>
                <?php while ($especialidad = $especialidades->fetch_assoc()): ?>
                            <label class="flex items-center space-x-4 p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-(--color-paciente-primary) cursor-pointer transition-all duration-200 w-full">

                                <input type="radio" name="especialidad_id" value="<?= $especialidad['id']; ?>" required class="w-5 h-5 text-(--color-paciente-primary) border-gray-300 focus:ring-(--color-paciente-primary) cursor-pointer">

                                <div class="flex flex-col text-left">
                                    <span class="font-semibold text-gray-800 text-base">
                                        <?php echo htmlspecialchars($especialidad['nombre']); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 font-normal">
                                        Seleccionar especialidad
                                    </span>
                                </div>

                            </label>
                <?php endwhile; ?>

                <input type="hidden" name="turno_id" value="<?= $idTurno; ?>">
            </div>

            <button type="submit" class="mt-auto self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Continuar
            </button>
        </form>
    </div>
</main>