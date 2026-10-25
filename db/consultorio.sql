-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2026 a las 20:14:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `consultorio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda_fija`
--

CREATE TABLE `agenda_fija` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `dia` varchar(100) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda_fija`
--

INSERT INTO `agenda_fija` (`id`, `doctor_id`, `especialidad_id`, `dia`, `hora_inicio`, `hora_fin`) VALUES
(1, 3, 1, '1', '08:00:00', '12:00:00'),
(2, 3, 1, '3', '08:00:00', '12:00:00'),
(3, 4, 2, '2', '09:00:00', '17:00:00'),
(4, 4, 2, '4', '09:00:00', '17:00:00'),
(5, 5, 3, '5', '13:00:00', '19:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coberturas_medica`
--

CREATE TABLE `coberturas_medica` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coberturas_medica`
--

INSERT INTO `coberturas_medica` (`id`, `nombre`) VALUES
(1, 'Osuthgra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctores`
--

CREATE TABLE `doctores` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `doctores`
--

INSERT INTO `doctores` (`id`, `usuario_id`) VALUES
(2, 3),
(3, 6),
(4, 7),
(5, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctor_especialidad`
--

CREATE TABLE `doctor_especialidad` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `doctor_especialidad`
--

INSERT INTO `doctor_especialidad` (`id`, `doctor_id`, `especialidad_id`) VALUES
(1, 3, 1),
(2, 4, 2),
(3, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`) VALUES
(1, 'Cardiología'),
(2, 'Pediatría'),
(3, 'Dermatología'),
(4, 'Neurología'),
(5, 'Ginecología'),
(6, 'Traumatología'),
(7, 'Oftalmología'),
(8, 'Gastroenterología'),
(9, 'Urología'),
(10, 'Otorrinolaringología'),
(11, 'Endocrinología'),
(12, 'Neumonología'),
(13, 'Reumatología'),
(14, 'Psiquiatría'),
(15, 'Oncología'),
(16, 'Nefrología'),
(17, 'Hematología'),
(18, 'Infectología'),
(19, 'Alergología'),
(20, 'Geriatría');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'Confirmado'),
(3, 'En sala de espera'),
(4, 'Atendido'),
(5, 'Cancelado por el paciente'),
(6, 'Cancelado por la clínica'),
(7, 'Ausente (No asistió)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cobertura_id` int(11) DEFAULT NULL,
  `dni` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `usuario_id`, `cobertura_id`, `dni`) VALUES
(2, 4, 1, '48640915'),
(3, 5, NULL, '11876454');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cobertura_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `dias_laborables` varchar(20) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `dias_laborables`, `hora_inicio`, `hora_fin`) VALUES
(1, 'Laboratorio de Análisis Clínicos', '1,2,3,4,5,6', '07:00:00', '12:00:00'),
(2, 'Radiografía (Rayos X)', '1,2,3,4,5', '08:00:00', '20:00:00'),
(3, 'Resonancia Magnética', '1,2,3,4,5,6', '08:00:00', '21:00:00'),
(4, 'Tomografía Computada (TAC)', '1,2,3,4,5', '08:00:00', '20:00:00'),
(5, 'Ecografía', '1,2,3,4,5', '08:00:00', '18:00:00'),
(6, 'Electrocardiograma (ECG)', '1,2,3,4,5', '09:00:00', '17:00:00'),
(7, 'Endoscopía Digestiva', '1,2,3,4,5', '08:00:00', '14:00:00'),
(8, 'Colonoscopía', '1,2,3,4,5', '08:00:00', '14:00:00'),
(9, 'Mamografía', '1,2,3,4,5', '09:00:00', '18:00:00'),
(10, 'Densitometría Ósea', '1,2,3,4,5', '09:00:00', '17:00:00'),
(11, 'Ergometría (Prueba de Esfuerzo)', '1,2,3,4,5', '08:00:00', '16:00:00'),
(12, 'Estudio Holter (24 hs)', '1,2,3,4,5', '09:00:00', '12:00:00'),
(13, 'Ecocardiograma', '1,2,3,4,5', '08:00:00', '16:00:00'),
(14, 'Espirometría', '1,2,3,4,5', '09:00:00', '15:00:00'),
(15, 'Papanicolau (PAP)', '1,2,3,4,5', '08:00:00', '18:00:00'),
(16, 'Kinesiología y Fisioterapia', '1,2,3,4,5', '08:00:00', '20:00:00'),
(17, 'Guardia Médica 24hs', '0,1,2,3,4,5,6', '00:00:00', '23:59:00'),
(18, 'Vacunatorio', '1,2,3,4,5,6', '08:00:00', '18:00:00'),
(19, 'Audiometría', '1,2,3,4,5', '09:00:00', '16:00:00'),
(20, 'Hemoterapia', '1,2,3,4,5', '08:00:00', '14:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `especialidad_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `fecha`, `hora`, `paciente_id`, `especialidad_id`, `servicio_id`, `doctor_id`, `estado_id`) VALUES
(2, '2026-10-26', '08:00:00', 3, 1, NULL, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `nombre`, `contrasena`, `rol`) VALUES
(2, 'santiagorobles107k@gmail.com', 'Santiago', '$2y$10$ZB5vx9mB.UyulpQYxoBz1.fkpA20LBXdXL356RHkpiJAy8l3vZSYO', 'admin'),
(3, 'eze@gmail.com', 'Ezequiel', '$2y$10$/7laXhW2ncFc7d0w7weVQ.vSGX3J3jmQBZnkjO3iBkKTWtUVCcgLG', 'admin'),
(4, 'arturo@gmail.com', 'Arturo', '$2y$10$8pkQhr8CXtobw9Tyh1ojpucvUMcpVMWmHnyacGijTYDSPHRFJbeA2', 'admin'),
(5, 'pepe@gmail.com', 'Pepe', '$2y$10$/w9dMR0IjIXxygmVwOQsyutN2YK71rmrnFN6KEuO28hF7i5tVqp3a', 'paciente'),
(6, 'dr.perez@clinica.com', 'Dr. Juan Pérez', '123456', 'doctor'),
(7, 'dra.gomez@clinica.com', 'Dra. Ana Gómez', '123456', 'doctor'),
(8, 'dr.martinez@clinica.com', 'Dr. Luis Martínez', '123456', 'doctor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda_fija`
--
ALTER TABLE `agenda_fija`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `coberturas_medica`
--
ALTER TABLE `coberturas_medica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `doctor_especialidad`
--
ALTER TABLE `doctor_especialidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `cobertura_id` (`cobertura_id`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cobertura_id` (`cobertura_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `especialidad_id` (`especialidad_id`),
  ADD KEY `servicios_id` (`servicio_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_uq` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda_fija`
--
ALTER TABLE `agenda_fija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `coberturas_medica`
--
ALTER TABLE `coberturas_medica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `doctores`
--
ALTER TABLE `doctores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `doctor_especialidad`
--
ALTER TABLE `doctor_especialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda_fija`
--
ALTER TABLE `agenda_fija`
  ADD CONSTRAINT `fk_agenda_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctores` (`id`),
  ADD CONSTRAINT `fk_agenda_especialidad` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`);

--
-- Filtros para la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD CONSTRAINT `fk_doctores_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `doctor_especialidad`
--
ALTER TABLE `doctor_especialidad`
  ADD CONSTRAINT `fk_doc_esp_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctores` (`id`),
  ADD CONSTRAINT `fk_doc_esp_especialidad` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_pacientes_cobertura` FOREIGN KEY (`cobertura_id`) REFERENCES `coberturas_medica` (`id`),
  ADD CONSTRAINT `fk_pacientes_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `planes`
--
ALTER TABLE `planes`
  ADD CONSTRAINT `fk_planes_cobertura` FOREIGN KEY (`cobertura_id`) REFERENCES `coberturas_medica` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_turnos_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctores` (`id`),
  ADD CONSTRAINT `fk_turnos_especialidad` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`),
  ADD CONSTRAINT `fk_turnos_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `fk_turnos_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `fk_turnos_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
