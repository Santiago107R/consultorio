<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

if (empty($_GET['id']) || empty($_GET['paciente_id']) || empty($_SESSION['usuario']['doctor_id'])) {
    header("Location: " . base_url);
    exit();
}

$idTurno = (int) $_GET['id'];
$idPaciente = (int) $_GET['paciente_id'];
$idDoctor = $_SESSION['usuario']['doctor_id'];

$query = "SELECT p.historia_clinica
          FROM turnos AS t
          INNER JOIN pacientes AS p ON p.id = t.paciente_id
          WHERE t.id = ? AND t.paciente_id = ? AND t.doctor_id = ? AND t.fecha <= CURDATE()";

$stmt = $conexion->prepare($query);
$stmt->bind_param('iii', $idTurno, $idPaciente, $idDoctor);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$paciente) {
    header("Location: " . base_url);
    exit();
}

$historia = $paciente['historia_clinica'] ?? '';
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Atender turno</h2>

        <form action="atendido.php" method="POST" class="w-[40%] flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">

            <div class="flex flex-col w-full space-y-4">
                <label for="historia" class="text-gray-800 text-center">Historia clínica</label>
                <textarea id="historia" readonly rows="8" class="w-full border border-border rounded-md p-2 bg-slate-50 resize-none overflow-y-auto"><?= htmlspecialchars($historia) ?></textarea>

                <label for="nueva_entrada" class="text-gray-800 text-center">Agregar a la historia clínica</label>
                <textarea name="nueva_entrada" id="nueva_entrada" rows="3" required class="w-full border border-border rounded-md p-2 resize-none overflow-y-auto"></textarea>

                <input type="hidden" name="turno_id" value="<?= $idTurno ?>">
            </div>

            <button type="submit" class="mt-4 self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Terminar
            </button>
        </form>
    </div>
</main>