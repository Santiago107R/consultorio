<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/consultorio/config/parameters.php';
require_once __DIR__ . '/../config/conexion.php';

$doctor_id = (int) ($_GET['id'] ?? 0);
if ($doctor_id <= 0) {
    header('Location: doctores.php');
    exit;
}

$stmtDoc = $conexion->prepare(
    "SELECT d.id, u.nombre, u.email
     FROM doctores d
     INNER JOIN usuarios u ON u.id = d.usuario_id
     WHERE d.id = ?"
);
$stmtDoc->bind_param("i", $doctor_id);
$stmtDoc->execute();
$doctor = $stmtDoc->get_result()->fetch_assoc();
$stmtDoc->close();

if (!$doctor) {
    header('Location: doctores.php?error=' . urlencode('El doctor no existe.'));
    exit;
}

// Especialidades asignadas al doctor
$stmtAsignadas = $conexion->prepare(
    "SELECT e.id, e.nombre
     FROM doctor_especialidad de
     INNER JOIN especialidades e ON e.id = de.especialidad_id
     WHERE de.doctor_id = ?
     ORDER BY e.nombre ASC"
);
$stmtAsignadas->bind_param("i", $doctor_id);
$stmtAsignadas->execute();
$asignadas = $stmtAsignadas->get_result();
$stmtAsignadas->close();

// Especialidades disponibles para asignar (que aún no tiene)
$stmtDisponibles = $conexion->prepare(
    "SELECT e.id, e.nombre
     FROM especialidades e
     WHERE e.id NOT IN (
         SELECT especialidad_id FROM doctor_especialidad WHERE doctor_id = ?
     )
     ORDER BY e.nombre ASC"
);
$stmtDisponibles->bind_param("i", $doctor_id);
$stmtDisponibles->execute();
$disponibles = $stmtDisponibles->get_result();
$stmtDisponibles->close();
?>
<?php require_once './layouts/header.php' ?>
<main class="w-4/5 mx-auto grid grid-cols-1 gap-5 mt-2 p-5">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Especialidades — <?= htmlspecialchars($doctor['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($doctor['email'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <a href="doctores.php"
           class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 transition">
            Volver
        </a>
    </div>

    <?php if (!empty($_GET['error'])): ?>
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
            <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- Asignar nuevas especialidades -->
    <form action="guardarEspecialidadesDoctor.php" method="POST"
          class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <input type="hidden" name="accion" value="agregar">
        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">

        <div class="flex-1 min-w-56">
            <label for="especialidades" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">
                Asignar especialidades
            </label>
            <select name="especialidades[]" id="especialidades" multiple
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 outline-none focus:border-[#2196F3]">
                <?php while ($disp = $disponibles->fetch_assoc()): ?>
                    <option value="<?= (int) $disp['id'] ?>">
                        <?= htmlspecialchars($disp['nombre'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <span class="text-xs text-gray-400">Mantené Ctrl para seleccionar más de una.</span>
        </div>

        <button type="submit"
                class="bg-[#2196F3] font-semibold rounded-lg cursor-pointer text-white px-4 py-2 text-sm hover:opacity-90 transition">
            Asignar
        </button>
    </form>

    <!-- Tabla de especialidades actuales del doctor -->
    <table class="grid grid-rows-[auto_1fr] max-h-[600px] overflow-hidden rounded-lg border border-gray-200 text-sm shadow-md text-center">
        <thead class="grid bg-gray-50 uppercase text-xs font-bold text-gray-600 border-b border-gray-200">
            <tr class="grid grid-flow-col auto-cols-fr">
                <th class="p-3">Especialidad</th>
                <th class="p-3 whitespace-nowrap">Acción</th>
            </tr>
        </thead>
        <tbody class="grid overflow-y-auto divide-y divide-gray-100">
            <?php if ($asignadas->num_rows === 0): ?>
                <tr class="grid grid-flow-col auto-cols-fr items-center">
                    <td class="p-4 text-gray-400 col-span-2">Este doctor no tiene especialidades asignadas.</td>
                </tr>
            <?php else: ?>
                <?php while ($esp = $asignadas->fetch_assoc()): ?>
                    <tr class="grid grid-flow-col auto-cols-fr items-center hover:bg-gray-50 transition-colors">
                        <td class="p-3 text-gray-700 font-medium">
                            <?= htmlspecialchars($esp['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td class="p-3">
                            <form action="guardarEspecialidadesDoctor.php" method="POST" class="inline">
                                <input type="hidden" name="accion" value="quitar">
                                <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
                                <input type="hidden" name="especialidad_id" value="<?= (int) $esp['id'] ?>">
                                <button type="submit"
                                        class="cursor-pointer rounded-lg border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-100 transition">
                                    Quitar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

</main>
</body>
</html>