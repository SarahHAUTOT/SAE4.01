CREATE OR REPLACE FUNCTION getCompMoy(IN compId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS FLOAT AS $$
DECLARE
	total FLOAT := 0;
	count_modules INTEGER := 0;
BEGIN
	SELECT COUNT(*) INTO count_modules FROM  CompMod 
	WHERE  compId = getMoyenne.compId;

	IF count_modules = 0 THEN
		RETURN NULL; -- No modules affected to the Competence 
	END IF;

	SELECT SUM(noteVal) INTO total
	FROM   Moyenne m JOIN CompMod cm ON m.modId = cm.modId
	WHERE  cm.compId = getMoyenne.compId AND m.anneeId = yearId AND m.etdId = studentId;

	RETURN total / count_modules;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION getRank(IN compId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS INTEGER AS $$
DECLARE
	student_rank INTEGER := NULL;
BEGIN
	SELECT RANK() OVER (ORDER BY m.noteVal ASC) INTO student_rank
	FROM  Moyenne m JOIN Competence c ON m.anneeId = yearId AND m.etdId = studentId
	JOIN  CompMod cm ON c.compId = cm.compId AND cm.modId = m.modId
	WHERE c.compId = compId;

	RETURN student_rank;
END;
$$ LANGUAGE plpgsql;
