<?php
    require_once './layouts/header.php';
    require_once __DIR__ . '/../config/conexion.php';

    $fechaFiltro = $_GET['fecha'] ?? '';
    $orden = match ($_GET['orden'] ?? 'asc') {
        'desc'  => 't.fecha DESC, t.hora DESC',
        default => 't.fecha ASC, t.hora ASC',
    };

    $query = "SELECT t.id, t.fecha, t.hora, t.paciente_id, t.fecha > CURDATE() AS es_proximo, e.nombre AS especialidad, up.nombre AS paciente, p.dni
          FROM turnos AS t
          LEFT JOIN especialidades AS e ON e.id = t.especialidad_id
          LEFT JOIN doctores AS d ON d.id = t.doctor_id
          INNER JOIN pacientes AS p ON p.id = t.paciente_id
          INNER JOIN usuarios AS up ON up.id = p.usuario_id
          INNER JOIN estados AS es ON es.id = t.estado_id
          WHERE d.id = ? AND es.id = ?";

    if ($fechaFiltro === 'hoy') {
        $query .= " AND t.fecha = CURDATE()";
    } elseif ($fechaFiltro === 'proximos') {
        $query .= " AND t.fecha > CURDATE()";
    }

    $query .= " ORDER BY $orden";

    $idDoctor = $_SESSION['usuario']['doctor_id'];
    $estado = 7;

    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $idDoctor, $estado);
    $stmt->execute();
    $turnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    function url_filtro(array $cambios): string {
        $params = array_merge($_GET, $cambios);
        $params = array_filter($params, fn($v) => $v !== null && $v !== '');
        return 'index.php' . ($params ? '?' . http_build_query($params) : '');
    }

    function clase_filtro(string $clave, ?string $valor): string {
        $activo = ($_GET[$clave] ?? null) === $valor;
        return $activo
            ? 'bg-(--color-paciente-primary) text-white rounded-lg px-2'
            : 'bg-white hover:bg-(--color-paciente-primary) hover:text-white rounded-lg px-2';
    }
?>

<main class="w-4/5 text-(--color-paciente-text) mx-auto space-y-8 p-5 font-medium">

    <div class="w-full flex flex-row justify-between text-gray-800">
        <h2>Turnos (<?= count($turnos) ?>)</h2>

        <div class="flex flex-row gap-2 items-center">
            <ion-icon name="funnel"></ion-icon>

            <a href="<?= url_filtro(['fecha' => null]) ?>" class="<?= clase_filtro('fecha', null) ?>">Todas las fechas</a>
            <a href="<?= url_filtro(['fecha' => 'hoy']) ?>" class="<?= clase_filtro('fecha', 'hoy') ?>">Hoy</a>
            <a href="<?= url_filtro(['fecha' => 'proximos']) ?>" class="<?= clase_filtro('fecha', 'proximos') ?>">Próximos</a>

            <a href="<?= url_filtro(['orden' => 'asc']) ?>" class="<?= clase_filtro('orden', 'asc') ?>">Ascendiente</a>
            <a href="<?= url_filtro(['orden' => 'desc']) ?>" class="<?= clase_filtro('orden', 'desc') ?>">Descendiente</a>
        </div>
    </div>

    <div id="tabla_body" class="min-h-90 grid grid-cols-1 overflow-y-auto rounded-lg text-sm">
        <table class="w-full border-separate border-spacing-0 text-center">
            <thead class="sticky top-0 z-10">
                <tr>
                    <th class="py-2 bg-slate-200 rounded-tl-lg">FECHA / HORA</th>
                    <th class="py-2 bg-slate-200">PACIENTE</th>
                    <th class="py-2 bg-slate-200">ESPECIALIDAD</th>
                    <th class="py-2 bg-slate-200 rounded-tr-lg">ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($turnos)): ?>
                    <tr>
                        <td colspan="4" class="py-8 bg-white rounded-b-lg text-gray-500">No hay turnos para mostrar</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($turnos as $turno): ?>
                    <tr class="group h-20">
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0 group-last:rounded-bl-lg">
                            <div class="flex flex-col">
                                <span><?= date('d/m/Y', strtotime($turno['fecha'])) ?></span>
                                <span><?= substr($turno['hora'], 0, 5) ?>hs</span>
                            </div>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0">
                            <div class="flex flex-col">
                                <span><?= htmlspecialchars($turno['paciente']) ?></span>
                                <span>DNI: <?= htmlspecialchars($turno['dni']) ?></span>
                            </div>
                        </td>
                        <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0">
                            <?= $turno['especialidad'] ?? '-' ?>
                        </td>
                       <td class="px-3 bg-white group-hover:bg-slate-50 transition-colors border-b border-slate-100 group-last:border-b-0 group-last:rounded-br-lg">
                            <?php if ($turno['es_proximo']): ?>
                                <button type="button" disabled class="bg-gray-300 text-gray-500 rounded-lg px-3 py-1 cursor-not-allowed">Atender</button>
                            <?php else: ?>
                                <a href="atender.php?id=<?= $turno['id'] ?>&paciente_id=<?= $turno['paciente_id'] ?>" class="bg-(--color-paciente-primary) text-white rounded-lg px-3 py-1">Atender</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>