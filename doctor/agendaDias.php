<?php
    require_once './layouts/header.php';

    if (empty($_POST['especialidad_id'])) {
        header("Location: " . base_url);
        exit();
    }

    $query = "SELECT a.id AS id, a.dia, a.hora_inicio FROM agenda_fija AS a WHERE a.doctor_id = ? AND a.especialidad_id = ?";

    $idDoctor = $_SESSION['usuario']['doctor_id'];
    $idEspecialidad = (int) $_POST['especialidad_id'];

    require_once __DIR__ . '/../config/conexion.php';

    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $idDoctor, $idEspecialidad);
    $stmt->execute();
    $agendas = $stmt->get_result();
    $stmt->close();

    $actuales = [];
    while ($fila = $agendas->fetch_assoc()) {
        $actuales[$fila['dia']] = substr($fila['hora_inicio'], 0, 5);
    }

    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 text-sm font-medium">
    <div class="w-full flex flex-col items-center justify-center space-y-4">
        <h2 class="text-base text-gray-800 border-b border-border">Asignar un consultorio</h2>

        <form action="guardarAgenda.php" method="POST" class="w-[40%] flex flex-col items-center bg-white border border-neutral-100 shadow-md rounded-lg p-4">

            <div class="flex flex-col w-full text-center space-y-4">
                <h2 class="text-base text-gray-800">Seleccione los días y su hora de inicio</h2>

                <div class="grid grid-cols-2 gap-4 p-2">
                    <?php foreach ($dias as $num => $nombre):
                        $marcado = isset($actuales[$num]);
                        $hora = $actuales[$num] ?? '';
                    ?>
                        <div class="flex flex-col space-y-2">
                            <input type="checkbox" id="dia-<?= $num; ?>" name="dias[]" value="<?= $num; ?>"
                                   class="peer sr-only dia-check" <?= $marcado ? 'checked' : ''; ?>>

                            <label for="dia-<?= $num; ?>"
                                   class="block cursor-pointer px-4 py-2 rounded-md border border-(--color-paciente-primary)
                                          bg-white text-(--color-paciente-primary) transition-colors
                                          peer-checked:bg-(--color-paciente-primary) peer-checked:text-white
                                          peer-focus-visible:ring-2 peer-focus-visible:ring-offset-1">
                                <?= $nombre; ?>
                            </label>

                            <input type="time" name="horas[<?= $num; ?>]" value="<?= $hora; ?>"
                                   class="hidden peer-checked:block w-full border border-neutral-300 rounded-md px-2 py-1 text-center"
                                   <?= $marcado ? 'required' : 'disabled'; ?>>
                        </div>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" name="especialidad_id" value="<?= $idEspecialidad; ?>">
            </div>

            <button type="submit" class="mt-4 self-end bg-(--color-paciente-primary) text-white px-4 py-1 rounded-md cursor-pointer">
                Guardar
            </button>
        </form>
    </div>
</main>

<script>
    document.querySelectorAll('.dia-check').forEach(chk => {
        const hora = chk.parentElement.querySelector('input[type="time"]');
        chk.addEventListener('change', () => {
            hora.disabled = !chk.checked;
            hora.required = chk.checked;
            if (chk.checked) hora.focus();
        });
    });
</script>