<?php
    require_once './layouts/header.php'; 
    require_once __DIR__ . '/../config/conexion.php';
    
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['paciente_id'])) {
        header('Location: ' . base_url);
        exit();
    }

    $idPaciente = $_SESSION['usuario']['paciente_id'];

    $query = "SELECT p.id, u.nombre AS nombre, p.dni, p.historia_clinica, c.nombre AS cobertura FROM pacientes AS p INNER JOIN usuarios AS u ON u.id = p.usuario_id LEFT JOIN coberturas_medica AS c ON c.id = p.cobertura_id WHERE p.id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $idPaciente);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $paciente = $resultado->fetch_assoc();
    $stmt->close();

    $hayHistoria = !empty($paciente['historia_clinica']);
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-6 p-5 text-sm">
    <div class="w-full flex flex-col items-start space-y-6 bg-white p-6 shadow rounded-lg">

        <div class="flex flex-col gap-1 text-gray-800">
            <span>
                <span class="font-medium text-gray-500">Nombre completo:</span>
                <?= htmlspecialchars($paciente['nombre']); ?>
            </span>
            <span>
                <span class="font-medium text-gray-500">DNI:</span>
                <?= htmlspecialchars($paciente['dni']); ?>
            </span>
            <span>
                <span class="font-medium text-gray-500">Cobertura:</span>
                <?= $hayHistoria ? htmlspecialchars($paciente['cobertura']) : 'Sin cobertura' ?>
            </span>
        </div>

        <section class="w-full space-y-3">
            <div class="flex flex-row justify-between items-center px-1">
                <h2 class="text-lg font-semibold text-gray-800 border-b border-slate-200 pb-2">
                    Historia Clínica
                </h2>

                <form action="generarPdf.php" method="POST" target="_blank">
                    <input type="hidden" name="historia" value="<?= htmlspecialchars($paciente['historia_clinica']) ?>">
                    
                    <button type="submit" 
                        class="px-4 py-2 rounded-lg font-medium transition-colors 
                        <?= $hayHistoria ? 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer' : 'bg-gray-200 text-gray-400 cursor-not-allowed' ?>"
                        <?= !$hayHistoria ? 'disabled' : '' ?>>
                        Descargar PDF
                    </button>
                </form>
            </div>

            <div class="w-full h-[60vh] overflow-y-auto p-4 border border-slate-200 rounded-lg bg-slate-50 text-gray-700 leading-relaxed whitespace-pre-line scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-100 hover:scrollbar-thumb-slate-400 scrollbar-thumb-rounded-full">
                <?= $hayHistoria ? htmlspecialchars($paciente['historia_clinica']) : 'Sin historia clínica' ?>
            </div>
        </section>

    </div>
</main>