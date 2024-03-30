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


CREATE OR REPLACE FUNCTION getRankComp(IN compId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
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

/* TODO
CREATE OR REPLACE FUNCTION getRankSem(...)
*/

/* TODO
CREATE OR REPLACE FUNCTION getRegroupAdmi(IN compId INTEGER, IN semId INTEGER, IN etdId INTEGER, IN yearId INTEGER)
RETURNS VARCHAR AS $$
DECLARE
	student_adm1 VARCHAR := NULL;
	student_adm2 VARCHAR := NULL;
	lastSemId    INTEGER := semId -1;
BEGIN
	SELECT admc1.admi INTO student_adm1, admc2.admi INTO student_adm2
	FROM AdmComp admc1 JOIN AdmComp admc2 ON admc2.compId=compId AND admc1.compId=compId
	JOIN Competence c1  ON c1.compId=admc1.compId
	JOIN Competence c2  ON c2.compId=admc2.compId
	JOIN AdmAnnee adma ON adma.etdId=etdId
	WHERE c.compId = compId AND anneeId = yearId
	      c1.semId = semId  AND c2.semId = lastSemId;
	
	// switch case
END;
$$ LANGUAGE plpgsql;
*/

CREATE OR REPLACE FUNCTION getNbAdmiUE(IN compId INTEGER, IN semId INTEGER, IN etdId INTEGER, IN yearId INTEGER)
RETURNS VARCHAR AS $$
BEGIN
	SELECT COUNT(admi)
	FROM AdmComp admc JOIN AdmAnnee adma ON adma.etdId=admc.etdId AND admc.etdId=etdId
	JOIN Competence c ON c.compId=admc.compId
	WHERE admi = 'ADM' AND c.semId = semId AND admc.compId=compId;
END;
$$ LANGUAGE plpgsql;
