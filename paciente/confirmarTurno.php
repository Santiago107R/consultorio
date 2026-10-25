<?php
require_once __DIR__ . '/../config/conexion.php';

session_start();

$usuario_id = $_SESSION['usuario']['id']; 

$queryPaciente = "SELECT id FROM pacientes WHERE usuario_id = ?";
$stmtPaciente = $conexion->prepare($queryPaciente);
$stmtPaciente->bind_param('i', $usuario_id);
$stmtPaciente->execute();
$resultadoPaciente = $stmtPaciente->get_result();

if ($filaPaciente = $resultadoPaciente->fetch_assoc()) {
    $paciente_id = $filaPaciente['id']; 
} else {
    die("Error: Tu usuario no tiene un perfil de paciente asociado en el sistema.");
}
$stmtPaciente->close();


$fecha = $_POST['fecha_turno'] ?? '';
$hora = $_POST['hora_turno'] ?? '';
$estado_id = 1;   

if (!empty($_POST['practica_id']) && !empty($_POST['tipo_practica'])) {
    $practica_id = $_POST['practica_id'];
    $tipo_practica = $_POST['tipo_practica'];

    $columnas = ['fecha', 'hora', 'paciente_id', 'estado_id'];
    $placeholders = ['?', '?', '?', '?'];
    
    $tipos = "ssii"; 
    $valores = [$fecha, $hora, $paciente_id, $estado_id];

    if ($tipo_practica == "servicio") {
        
        $columnas[] = 'servicio_id';
        $placeholders[] = '?';
        $tipos .= 'i';
        $valores[] = $practica_id;

    } elseif ($tipo_practica == "especialidad" && !empty($_POST['doctor_id'])) {
        
        $doctor_id = $_POST['doctor_id'];
        
        array_push($columnas, 'especialidad_id', 'doctor_id');
        array_push($placeholders, '?', '?');
        $tipos .= 'ii';
        array_push($valores, $practica_id, $doctor_id);
        
    }

    $query = "INSERT INTO turnos (" . implode(", ", $columnas) . ") VALUES (" . implode(", ", $placeholders) . ")";

    $stmt = $conexion->prepare($query);
    
    $stmt->bind_param($tipos, ...$valores);
    
    if ($stmt->execute()) {
        header("Location: turnos.php");
        exit();
    } else {
        echo "Error al guardar el turno: " . $stmt->error;
    }
    
    $stmt->close();
}
?>