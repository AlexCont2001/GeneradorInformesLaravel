DELIMITER $$
CREATE PROCEDURE sp_calcularPromediosAsignatura(
    IN estudiantes_ids VARCHAR(255),
    IN v_ponderacion_id INT
)

BEGIN
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
END
DELIMITER;