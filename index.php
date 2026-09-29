<?php

session_start();

if (isset($_SESSION['usuario'])) {
    if ($_SESSION['usuario']['rol'] == "admin") {
        header("Location: ./admin/pacientes.php");
    } else {
        header("Location: ./auth/login.php");
    }
} else {
    header("Location: ./auth/login.php");
}
