<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

session_start();

$_SESSION = [];

session_destroy();
http_response_code(204);
