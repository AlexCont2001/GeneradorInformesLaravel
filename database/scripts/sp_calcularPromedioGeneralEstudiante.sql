DELIMITER $$
CREATE PROCEDURE sp_calcularPromedioGeneralEstudiante(
    IN v_curso_id INT
)
BEGIN
    DECLARE done_est INT DEFAULT 0;
    DECLARE v_estudiante_id INT;
    DECLARE v_promedio_general FLOAT;
    DECLARE curEstudiante CURSOR FOR
        SELECT id FROM estudiantes
        WHERE curso_id = v_curso_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_est = 1;

    open curEstudiante;

    estudiante_loop: LOOP
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

END $$
DELIMITER;