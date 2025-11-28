DELIMITER $$
CREATE PROCEDURE sp_calcularPromedioCurso(
    IN v_curso_id INT
)
BEGIN
    DECLARE v_promedio_curso FLOAT;

    SELECT ROUND(AVG(promedio),1)
    INTO v_promedio_curso
    FROM estudiantes
    WHERE curso_id = v_curso_id;

    UPDATE cursos
    SET promedio = v_promedio_curso
    WHERE id = v_curso_id;

END $$
DELIMITER;