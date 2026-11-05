<?php

define('base_url', 'http://localhost/consultorio/');
define('api_url', 'http://localhost/consultorio/api/');

define('badge_status_classes', [
    'Pendiente' => 'bg-yellow-100 text-yellow-800',
    'Confirmado' => 'bg-green-100 text-green-800',
    'Cancelado_por_la_clínica' => 'bg-red-100 text-red-800',
    'Cancelado_por_el_paciente' => 'bg-gray-100 text-gray-700',
    'Atendido' => 'bg-blue-100 text-blue-800',
    'Ausente' => 'bg-orange-100 text-orange-800',
]);
