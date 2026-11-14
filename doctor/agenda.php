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
        
        <form action="consultorioAsignado.php" method="POST" class="w-[40%] h-64 flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">
            
            <div class="flex flex-col w-full text-center space-y-4">
                <label for="especialidad" class="text-gray-800">Agenda fija</label>
                
                <select name="especialidad_id" id="especialidad" class="mx-auto border border-border rounded-md">
                    <?php while ($especialidad = $especialidades->fetch_assoc()): ?>
                        <option value="<?= $especialidad['id'] ?>"><?= $especialidad['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>

                <input type="hidden" name="turno_id" value="<?= $idTurno; ?>">
            </div>

            <button type="submit" class="mt-auto self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Asignar
            </button>
        </form>
    </div>
</main>