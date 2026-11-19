<?php
require_once './layouts/header.php';
require_once __DIR__ . '/../config/conexion.php';

$query = "SELECT id, nombre FROM especialidades ORDER BY nombre";
$stmt = $conexion->prepare($query);
$stmt->execute();
$especialidades = $stmt->get_result();

$inputClass = "w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 placeholder-gray-400 outline-none transition focus:border-(--color-paciente-primary) focus:ring-2 focus:ring-(--color-paciente-primary)/20";
$labelClass = "block text-left text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1";
?>

<main class="mx-auto w-11/12 max-w-lg p-5 text-sm font-medium text-(--color-paciente-text)">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Crear un doctor</h2>
        <p class="mt-1 text-xs font-normal text-gray-500">Completá los datos y elegí sus especialidades.</p>
    </div>

    <form action="guardarDoctor.php" method="POST"
          class="space-y-5 rounded-2xl border border-neutral-100 bg-white p-6 shadow-md">

        <?php if (!empty($_GET['error'])): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="nombre" class="<?= $labelClass ?>">Nombre</label>
            <input type="text" name="nombre" id="nombre" required autocomplete="name"
                   placeholder="Nombre y apellido" class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="email" class="<?= $labelClass ?>">Email</label>
            <input type="email" name="email" id="email" required autocomplete="email"
                   placeholder="doctor@correo.com" class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="contrasena" class="<?= $labelClass ?>">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" required minlength="6"
                   autocomplete="new-password" placeholder="Mínimo 6 caracteres" class="<?= $inputClass ?>">
        </div>

        <fieldset id="grupo-especialidades">
            <legend class="<?= $labelClass ?>">Especialidades</legend>
            <p class="mb-2 text-left text-xs font-normal text-gray-400">Podés elegir más de una.</p>

            <?php if (!$especialidades || $especialidades->num_rows === 0): ?>
                <div class="rounded-lg border border-dashed bg-gray-50 p-4 text-center text-gray-500">
                    No se encontraron especialidades
                </div>
            <?php else: ?>
                <div class="grid max-h-56 grid-cols-1 gap-2 overflow-y-auto pr-1 sm:grid-cols-2">
                    <?php while ($esp = $especialidades->fetch_assoc()): ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="especialidades[]"
                                   value="<?= (int) $esp['id'] ?>" class="peer sr-only">
                            <span class="flex items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-left text-sm text-gray-700 transition
                                         hover:border-(--color-paciente-primary)
                                         peer-checked:border-(--color-paciente-primary)
                                         peer-checked:bg-(--color-paciente-primary)
                                         peer-checked:text-white
                                         peer-focus-visible:ring-2 peer-focus-visible:ring-(--color-paciente-primary)/40">
                                <?= htmlspecialchars($esp['nombre']) ?>
                            </span>
                        </label>
                    <?php endwhile; ?>
                </div>
                <p id="error-especialidades" class="mt-2 hidden text-left text-xs text-red-500">
                    Elegí al menos una especialidad.
                </p>
            <?php endif; ?>
        </fieldset>

        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
            <a href="doctores.php"
               class="rounded-lg border border-gray-300 px-4 py-2 text-gray-600 transition hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="cursor-pointer rounded-lg bg-(--color-paciente-primary) px-5 py-2 font-semibold text-white shadow-sm transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-(--color-paciente-primary)/40">
                Crear
            </button>
        </div>
    </form>
</main>

<script>
    const form = document.querySelector('form');
    const error = document.getElementById('error-especialidades');

    form.addEventListener('submit', (e) => {
        const marcadas = form.querySelectorAll('input[name="especialidades[]"]:checked');
        if (marcadas.length === 0) {
            e.preventDefault();
            error?.classList.remove('hidden');
            error?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    form.addEventListener('change', (e) => {
        if (e.target.name === 'especialidades[]') error?.classList.add('hidden');
    });
</script>