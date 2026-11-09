<?php

session_start();

if (isset($_SESSION['usuario'])) {
    if ($_SESSION['usuario']['rol'] == "admin") {
        header("Location: ./admin/index.php");
    } elseif ($_SESSION['usuario']['rol'] == "paciente") {
        header("Location: ./paciente/index.php");
    } elseif ($_SESSION['usuario']['rol'] == "secretario") {
        header("Location: ./secretario/index.php");
    } else {
        header("Location: ./auth/login.php");
    }
} else {
    header("Location: ./auth/login.php");
}
