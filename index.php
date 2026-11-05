<?php

session_start();

if (isset($_SESSION['usuario'])) {
    if ($_SESSION['usuario']['rol'] == "admin") {
        header("Location: ./admin/pacientes.php");
    } elseif ($_SESSION['usuario']['rol'] == "paciente") {
        header("Location: ./paciente/proximosTurnos.php");
    } else {
        header("Location: ./auth/login.php");
    }
} else {
    header("Location: ./auth/login.php");
}
