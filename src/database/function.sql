CREATE OR REPLACE FUNCTION getCompMoy(IN semesterId INTEGER, IN compId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS FLOAT AS $$
DECLARE
	tot_note FLOAT := 0;
	tot_coef FLOAT := 0;
BEGIN
	SELECT SUM(modCoef) INTO tot_coef
	FROM  AdmComp admc JOIN Competence c ON c.compId = admc.compId 
	WHERE c.compId = compId AND semId = semesterId AND anneeId = yearId;

	IF tot_coef = 0 THEN
		RETURN NULL; -- No modules affected to the Competence 
	END IF;

	SELECT SUM(noteVal * modCoef) INTO tot_note
	FROM  Moyenne m JOIN CompMod cm ON m.modId = cm.modId
	JOIN  Competence c ON c.compId = cm.compId
	WHERE cm.compId = compId AND m.anneeId = yearId 
		  AND m.etdId = studentId AND semId = semesterId;

	RETURN tot_note / tot_coef;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION getSemMoy(IN semesterId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS FLOAT AS $$
DECLARE
	total FLOAT := 0;
	count_comps INTEGER := 0;
BEGIN
	SELECT COUNT(c.compId) INTO count_comps 
	FROM  AdmComp admc JOIN Competence c ON c.compId=admc.compId 
	WHERE semId = semesterId AND anneeId = yearId;

	IF count_comps = 0 THEN
		RETURN NULL; -- No competence for the semester 
	END IF;

	SELECT SUM( getCompMoy(semesterId, c.compId, etdId, yearId) ) INTO total
	FROM  AdmComp admc JOIN Competence c ON c.compId = admc.compId 
	WHERE etdId = studentId AND semId = semesterId AND anneeId = yearId;

	RETURN total / count_comps;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION getRankSem(IN semesterId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS INTEGER AS $$
DECLARE
	nb_student INTEGER := NULL;
	student_rank INTEGER := NULL;
BEGIN
	-- Getting the moy of the semester for every student
	CREATE TEMP TABLE IF NOT EXISTS etd_moySem AS
	SELECT etdId, getSemMoy(semesterId, etdId, yearId) AS moySem
	FROM  AdmComp admc JOIN Competence c ON c.compId = admc.compId
	WHERE anneeId = yearId AND semId = semesterId;

	SELECT COUNT(etdId) INTO nb_student FROM etd_moySem;
	IF nb_student = 0 THEN
		RETURN NULL; -- No students for this comp
	END IF;

	-- Getting the rank of one student
	SELECT RANK() OVER (ORDER BY moySem ASC) INTO student_rank
	FROM  etd_moySem 
	WHERE etdId = studentId;

	RETURN student_rank;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION getRCUE(IN semesterId INTEGER, IN compId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS VARCHAR AS $$
DECLARE
	student_adm1 VARCHAR := NULL;
	student_adm2 VARCHAR := NULL;
	lastSemId    INTEGER := semesterId - 1;
BEGIN
	-- Getting the admi of the two last semester for one comp
	SELECT admc1.admi INTO student_adm1
	FROM AdmComp admc1
	JOIN Competence c1 ON c1.compId=admc1.compId
	WHERE admc1.compId = compId AND admc1.etdId = studentId AND
		  admc1.anneeId = yearId AND  c1.semId = semesterId;

	SELECT admc2.admi INTO student_adm2
	FROM AdmComp admc2
	JOIN Competence c2 ON c2.compId=admc2.compId
	WHERE admc2.compId = compId AND admc2.etdId = studentId AND
		  admc2.anneeId = yearId AND  c2.semId = lastSemId;

	-- switch case TODO
	RETURN student_adm1; -- Temporarily returning only student_adm1
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION getNbAdmiUE(IN semesterId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS INTEGER AS $$
DECLARE
	nb_admi INTEGER := NULL;
BEGIN
	SELECT COUNT(admi) INTO nb_admi
	FROM AdmComp admc JOIN Competence c ON c.compId=admc.compId
	WHERE admi = 'ADM' AND c.semId = semesterId AND etdId = studentId AND anneeId = yearId;

	RETURN nb_admi;
END;
$$ LANGUAGE plpgsql;
