DELIMITER $$

CREATE PROCEDURE sp_calcularPromediosPonderacion(
    IN ponderacion_ids VARCHAR(255)
)
BEGIN
    DECLARE v_ponderacion_id INT;
    DECLARE done_pond INT DEFAULT 0;
    DECLARE V_N1_PER, V_N2_PER, V_N3_PER, V_N4_PER, V_N5_PER, V_N6_PER FLOAT;
    DECLARE v_estudiante_id INT;
    DECLARE v_promedio FLOAT;

    DECLARE curPond CURSOR FOR
        SELECT id 
        FROM ponderaciones
        WHERE FIND_IN_SET(id, ponderacion_ids);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_pond = 1;

    OPEN curPond;

    ponderacion_loop: LOOP

        FETCH curPond INTO v_ponderacion_id;
        IF done_pond THEN LEAVE ponderacion_loop; END IF;

        SELECT n1_per, n2_per, n3_per, n4_per, n5_per, n6_per
        INTO V_N1_PER, V_N2_PER, V_N3_PER, V_N4_PER, V_N5_PER, V_N6_PER
        FROM ponderaciones
        WHERE id = v_ponderacion_id;

        BEGIN
            DECLARE done_est INT DEFAULT 0;
            DECLARE curEst CURSOR FOR
                SELECT DISTINCT estudiante_id
                FROM calificaciones
                WHERE ponderacion_id = v_ponderacion_id;

            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_est = 1;

            OPEN curEst;

            estudiante_loop: LOOP
                FETCH curEst INTO v_estudiante_id;
                IF done_est THEN LEAVE estudiante_loop; END IF;

                SELECT ROUND(
                    c.n1*(V_N1_PER/100) +
                    c.n2*(V_N2_PER/100) +
                    c.n3*(V_N3_PER/100) +
                    c.n4*(V_N4_PER/100) +
                    c.n5*(V_N5_PER/100) +
                    c.n6*(V_N6_PER/100)
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

END $$

DELIMITER ;
