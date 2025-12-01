-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2025 a las 04:30:27
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
-- Base de datos: `reportes_db`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_calcularPromedioCurso` (IN `v_curso_id` INT)   BEGIN
    DECLARE v_promedio_curso FLOAT;

    SELECT ROUND(AVG(promedio),1)
    INTO v_promedio_curso
    FROM estudiantes
    WHERE curso_id = v_curso_id;

    UPDATE cursos
    SET promedio = v_promedio_curso
    WHERE id = v_curso_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_calcularPromedioGeneralEstudiante` (IN `v_curso_id` INT)   BEGIN
    DECLARE done_est INT DEFAULT 0;
    DECLARE v_estudiante_id INT;
    DECLARE v_promedio_general FLOAT;
    DECLARE curEstudiante CURSOR FOR
        SELECT id FROM estudiantes
        WHERE curso_id = v_curso_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_est = 1;

    open curEstudiante;

    estudiante_loop:LOOP
        FETCH curEstudiante INTO v_estudiante_id;
        IF done_est THEN
            LEAVE estudiante_loop;
        END IF;

        SELECT ROUND(AVG(c.promedio),1)
        INTO v_promedio_general
        FROM calificaciones c
        INNER JOIN ponderaciones p ON c.ponderacion_id = p.id
        INNER JOIN asignaturas a ON p.asignatura_id = a.id
        WHERE c.estudiante_id = v_estudiante_id AND a.ponderable = 1;

        UPDATE estudiantes
        SET promedio = v_promedio_general
        WHERE id = v_estudiante_id;

    END LOOP;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_calcularPromediosAsignatura` (IN `v_ponderacion_id` INT, IN `estudiantes_ids` VARCHAR(255))   BEGIN
    DECLARE v_estudiante_id INT;
    DECLARE done INT DEFAULT 0;
    DECLARE promedioAsignatura FLOAT;

    -- Cursor para recorrer los estudiantes enviados
    DECLARE cur CURSOR FOR
        SELECT id FROM estudiantes
        WHERE FIND_IN_SET(id, estudiantes_ids);

    -- Manejador para detectar fin del cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    leer_loop: LOOP
        FETCH cur INTO v_estudiante_id;
        IF done THEN
            LEAVE leer_loop;
        END IF;

        -- Calcular promedio ponderado del estudiante actual
        SELECT ROUND(SUM(
    c.n1*(p.n1_per/100) + 
    c.n2*(p.n2_per/100) + 
    c.n3*(p.n3_per/100) + 
    c.n4*(p.n4_per/100) + 
    c.n5*(p.n5_per/100) + 
    c.n6*(p.n6_per/100)
), 1)
        INTO promedioAsignatura
        FROM calificaciones c
        INNER JOIN ponderaciones p ON c.ponderacion_id = p.id
        WHERE p.id = v_ponderacion_id
          AND c.estudiante_id = v_estudiante_id;

        -- Actualizar solo la calificación correspondiente
        UPDATE calificaciones
        SET promedio = promedioAsignatura
        WHERE estudiante_id = v_estudiante_id
          AND ponderacion_id = v_ponderacion_id;

    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_calcularPromediosPonderacion` (IN `ponderacion_ids` VARCHAR(255))  DETERMINISTIC BEGIN
    DECLARE v_ponderacion_id INT;
    DECLARE done_pond INT DEFAULT 0;
    DECLARE v_N1_PER, v_N2_PER, v_N3_PER, v_N4_PER, v_N5_PER, v_N6_PER FLOAT;
    DECLARE v_estudiante_id INT;
    DECLARE v_promedio FLOAT;

    DECLARE curPond CURSOR FOR
        SELECT id 
        FROM ponderaciones
        WHERE FIND_IN_SET(id, ponderacion_ids);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_pond = 1;

    OPEN curPond;

    ponderacion_loop:LOOP

        FETCH curPond INTO v_ponderacion_id;
        IF done_pond THEN LEAVE ponderacion_loop; END IF;

        -- Obtener porcentajes
        SELECT n1_per, n2_per, n3_per, n4_per, n5_per, n6_per
        INTO v_N1_PER, v_N2_PER, v_N3_PER, v_N4_PER, v_N5_PER, v_N6_PER
        FROM ponderaciones
        WHERE id = v_ponderacion_id;
        
SELECT v_N3_PER;

        -- Ahora procesar cada estudiante SIN cursor interno
        BEGIN
            DECLARE done_est INT DEFAULT 0;
            DECLARE curEst CURSOR FOR
                SELECT DISTINCT estudiante_id
                FROM calificaciones
                WHERE ponderacion_id = v_ponderacion_id;

            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_est = 1;

            OPEN curEst;

            estudiante_loop:LOOP
                FETCH curEst INTO v_estudiante_id;
                IF done_est THEN LEAVE estudiante_loop; END IF;

                SELECT ROUND(
                    c.n1*(v_N1_PER/100) +
                    c.n2*(v_N2_PER/100) +
                    c.n3*(v_N3_PER/100) +
                    c.n4*(v_N4_PER/100) +
                    c.n5*(v_N5_PER/100) +
                    c.n6*(v_N6_PER/100)
                , 1)
                INTO v_promedio
                FROM calificaciones c
                WHERE c.estudiante_id = v_estudiante_id
                AND c.ponderacion_id = v_ponderacion_id;
                UPDATE calificaciones
                SET promedio = v_promedio
                WHERE estudiante_id = v_estudiante_id
                AND ponderacion_id = v_ponderacion_id;

            END LOOP;

            CLOSE curEst;
        END;

    END LOOP;

    CLOSE curPond;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ponderable` tinyint(1) NOT NULL,
  `conceptual` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `nombre`, `ponderable`, `conceptual`) VALUES
(1, 'Matemáticas', 1, 0),
(2, 'Lenguaje', 1, 0),
(3, 'Historia', 1, 0),
(4, 'Ciencias Naturales', 1, 0),
(5, 'Inglés', 1, 0),
(6, 'Tecnología', 0, 0),
(7, 'Educación Física', 1, 0),
(8, 'Taller', 1, 0),
(9, 'Orientación', 0, 1),
(10, 'Religión', 0, 1),
(11, 'Música', 1, 0),
(12, 'Artes Visuales', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `n1` float NOT NULL,
  `n2` float NOT NULL,
  `n3` float NOT NULL,
  `n4` float NOT NULL,
  `n5` float NOT NULL,
  `n6` float NOT NULL,
  `promedio` float NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `ponderacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `profesor` varchar(255) DEFAULT NULL,
  `promedio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `profesor`, `promedio`) VALUES
(1, 'Primero Básico', '', 0),
(2, 'Segundo Básico', NULL, 0),
(3, 'Tercero Básico', NULL, 0),
(4, 'Cuarto Básico', NULL, 0),
(5, 'Quinto Básico', NULL, 0),
(6, 'Sexto Básico', NULL, 0),
(7, 'Séptimo Básico', NULL, 0),
(8, 'Octavo Básico', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellido_paterno` varchar(255) NOT NULL,
  `apellido_materno` varchar(255) NOT NULL,
  `rut` varchar(15) NOT NULL,
  `promedio` float NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `estudiantes`
--
DELIMITER $$
CREATE TRIGGER `trg_crear_calificaciones_al_insertar_estudiante` AFTER INSERT ON `estudiantes` FOR EACH ROW BEGIN
    INSERT INTO calificaciones (estudiante_id, ponderacion_id, n1, n2, n3, n4, n5, n6, promedio)
    SELECT NEW.id, p.id, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0
    FROM ponderaciones p
    WHERE p.curso_id = NEW.curso_id
      AND NOT EXISTS (
          SELECT 1 FROM calificaciones c
          WHERE c.estudiante_id = NEW.id
            AND c.ponderacion_id = p.id
      );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ponderaciones`
--

CREATE TABLE `ponderaciones` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `n1_per` float NOT NULL,
  `n2_per` float NOT NULL,
  `n3_per` float NOT NULL,
  `n4_per` float NOT NULL,
  `n5_per` float NOT NULL,
  `n6_per` float NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ponderaciones`
--

INSERT INTO `ponderaciones` (`id`, `cantidad`, `n1_per`, `n2_per`, `n3_per`, `n4_per`, `n5_per`, `n6_per`, `asignatura_id`, `curso_id`) VALUES
(128, 6, 0, 0, 0, 0, 0, 0, 1, 1),
(129, 6, 0, 0, 0, 0, 0, 0, 1, 2),
(130, 6, 0, 0, 0, 0, 0, 0, 1, 3),
(131, 6, 0, 0, 0, 0, 0, 0, 1, 4),
(132, 6, 0, 0, 0, 0, 0, 0, 1, 5),
(133, 6, 0, 0, 0, 0, 0, 0, 1, 6),
(134, 6, 0, 0, 0, 0, 0, 0, 1, 7),
(135, 6, 0, 0, 0, 0, 0, 0, 1, 8),
(136, 6, 0, 0, 0, 0, 0, 0, 2, 1),
(137, 6, 0, 0, 0, 0, 0, 0, 2, 2),
(138, 6, 0, 0, 0, 0, 0, 0, 2, 3),
(139, 6, 0, 0, 0, 0, 0, 0, 2, 4),
(140, 6, 0, 0, 0, 0, 0, 0, 2, 5),
(141, 6, 0, 0, 0, 0, 0, 0, 2, 6),
(142, 6, 0, 0, 0, 0, 0, 0, 2, 7),
(143, 6, 0, 0, 0, 0, 0, 0, 2, 8),
(144, 6, 0, 0, 0, 0, 0, 0, 3, 1),
(145, 6, 0, 0, 0, 0, 0, 0, 3, 2),
(146, 6, 0, 0, 0, 0, 0, 0, 3, 3),
(147, 6, 0, 0, 0, 0, 0, 0, 3, 4),
(148, 6, 0, 0, 0, 0, 0, 0, 3, 5),
(149, 6, 0, 0, 0, 0, 0, 0, 3, 6),
(150, 6, 0, 0, 0, 0, 0, 0, 3, 7),
(151, 6, 0, 0, 0, 0, 0, 0, 3, 8),
(152, 6, 0, 0, 0, 0, 0, 0, 4, 1),
(153, 6, 0, 0, 0, 0, 0, 0, 4, 2),
(154, 6, 0, 0, 0, 0, 0, 0, 4, 3),
(155, 6, 0, 0, 0, 0, 0, 0, 4, 4),
(156, 6, 0, 0, 0, 0, 0, 0, 4, 5),
(157, 6, 0, 0, 0, 0, 0, 0, 4, 6),
(158, 6, 0, 0, 0, 0, 0, 0, 4, 7),
(159, 6, 0, 0, 0, 0, 0, 0, 4, 8),
(160, 6, 0, 0, 0, 0, 0, 0, 5, 1),
(161, 6, 0, 0, 0, 0, 0, 0, 5, 2),
(162, 6, 0, 0, 0, 0, 0, 0, 5, 3),
(163, 6, 0, 0, 0, 0, 0, 0, 5, 4),
(164, 6, 0, 0, 0, 0, 0, 0, 5, 5),
(165, 6, 0, 0, 0, 0, 0, 0, 5, 6),
(166, 6, 0, 0, 0, 0, 0, 0, 5, 7),
(167, 6, 0, 0, 0, 0, 0, 0, 5, 8),
(168, 6, 0, 0, 0, 0, 0, 0, 6, 1),
(169, 6, 0, 0, 0, 0, 0, 0, 6, 2),
(170, 6, 0, 0, 0, 0, 0, 0, 6, 3),
(171, 6, 0, 0, 0, 0, 0, 0, 6, 4),
(172, 6, 0, 0, 0, 0, 0, 0, 6, 5),
(173, 6, 0, 0, 0, 0, 0, 0, 6, 6),
(174, 6, 0, 0, 0, 0, 0, 0, 6, 7),
(175, 6, 0, 0, 0, 0, 0, 0, 6, 8),
(176, 6, 0, 0, 0, 0, 0, 0, 7, 1),
(177, 6, 0, 0, 0, 0, 0, 0, 7, 2),
(178, 6, 0, 0, 0, 0, 0, 0, 7, 3),
(179, 6, 0, 0, 0, 0, 0, 0, 7, 4),
(180, 6, 0, 0, 0, 0, 0, 0, 7, 5),
(181, 6, 0, 0, 0, 0, 0, 0, 7, 6),
(182, 6, 0, 0, 0, 0, 0, 0, 7, 7),
(183, 6, 0, 0, 0, 0, 0, 0, 7, 8),
(184, 6, 0, 0, 0, 0, 0, 0, 8, 1),
(185, 6, 0, 0, 0, 0, 0, 0, 8, 2),
(186, 6, 0, 0, 0, 0, 0, 0, 8, 3),
(187, 6, 0, 0, 0, 0, 0, 0, 8, 4),
(188, 6, 0, 0, 0, 0, 0, 0, 8, 5),
(189, 6, 0, 0, 0, 0, 0, 0, 8, 6),
(190, 6, 0, 0, 0, 0, 0, 0, 8, 7),
(191, 6, 0, 0, 0, 0, 0, 0, 8, 8),
(192, 6, 0, 0, 0, 0, 0, 0, 9, 1),
(193, 6, 0, 0, 0, 0, 0, 0, 9, 2),
(194, 6, 0, 0, 0, 0, 0, 0, 9, 3),
(195, 6, 0, 0, 0, 0, 0, 0, 9, 4),
(196, 6, 0, 0, 0, 0, 0, 0, 9, 5),
(197, 6, 0, 0, 0, 0, 0, 0, 9, 6),
(198, 6, 0, 0, 0, 0, 0, 0, 9, 7),
(199, 6, 0, 0, 0, 0, 0, 0, 9, 8),
(200, 6, 0, 0, 0, 0, 0, 0, 10, 1),
(201, 6, 0, 0, 0, 0, 0, 0, 10, 2),
(202, 6, 0, 0, 0, 0, 0, 0, 10, 3),
(203, 6, 0, 0, 0, 0, 0, 0, 10, 4),
(204, 6, 0, 0, 0, 0, 0, 0, 10, 5),
(205, 6, 0, 0, 0, 0, 0, 0, 10, 6),
(206, 6, 0, 0, 0, 0, 0, 0, 10, 7),
(207, 6, 0, 0, 0, 0, 0, 0, 10, 8),
(208, 6, 0, 0, 0, 0, 0, 0, 11, 1),
(209, 6, 0, 0, 0, 0, 0, 0, 11, 2),
(210, 6, 0, 0, 0, 0, 0, 0, 11, 3),
(211, 6, 0, 0, 0, 0, 0, 0, 11, 4),
(212, 6, 0, 0, 0, 0, 0, 0, 11, 5),
(213, 6, 0, 0, 0, 0, 0, 0, 11, 6),
(214, 6, 0, 0, 0, 0, 0, 0, 11, 7),
(215, 6, 0, 0, 0, 0, 0, 0, 11, 8),
(216, 6, 0, 0, 0, 0, 0, 0, 12, 1),
(217, 6, 0, 0, 0, 0, 0, 0, 12, 2),
(218, 6, 0, 0, 0, 0, 0, 0, 12, 3),
(219, 6, 0, 0, 0, 0, 0, 0, 12, 4),
(220, 6, 0, 0, 0, 0, 0, 0, 12, 5),
(221, 6, 0, 0, 0, 0, 0, 0, 12, 6),
(222, 6, 0, 0, 0, 0, 0, 0, 12, 7),
(223, 6, 0, 0, 0, 0, 0, 0, 12, 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calificacion_ponderacion` (`ponderacion_id`),
  ADD KEY `calificacion_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_curso` (`curso_id`);

--
-- Indices de la tabla `ponderaciones`
--
ALTER TABLE `ponderaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ponderacion_curso` (`curso_id`),
  ADD KEY `ponderacion_asignatura` (`asignatura_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ponderaciones`
--
ALTER TABLE `ponderaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificacion_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificacion_ponderacion` FOREIGN KEY (`ponderacion_id`) REFERENCES `ponderaciones` (`id`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiante_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Filtros para la tabla `ponderaciones`
--
ALTER TABLE `ponderaciones`
  ADD CONSTRAINT `ponderacion_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `ponderacion_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
