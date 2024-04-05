CREATE OR REPLACE FUNCTION getCompMoy(IN compIdd INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS FLOAT AS $$
DECLARE
	moy FLOAT:= 0;
BEGIN
	SELECT SUM(noteVal * cm.modCoef) / SUM(cm.modCoef) INTO moy
	FROM Moyenne m
	JOIN CompMod cm ON m.modId = cm.modId
	JOIN Competence c ON c.compId = cm.compId
	WHERE c.compId = compIdd 
	AND   m.etdId = studentId
	AND   anneeId = yearId; 

	RETURN moy;

END;
$$ LANGUAGE plpgsql;





CREATE OR REPLACE FUNCTION getSemMoy(IN semesterId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS FLOAT AS $$
DECLARE
	total FLOAT := 0;
	count_comps INTEGER := 0;
BEGIN
	SELECT COUNT(*) INTO count_comps 
	FROM   Competence
	WHERE  semid = semesterId;

	IF count_comps = 0 THEN
		RETURN NULL; -- No competence for the semester 
	END IF;

	SELECT SUM( getCompMoy(c.compId, etdId, yearId) ) INTO total
	FROM  AdmComp admc JOIN Competence c ON c.compId = admc.compId 
	WHERE etdId = studentId AND semId = semesterId AND anneeId = yearId;

	RETURN total / count_comps;
END;
$$ LANGUAGE plpgsql;










CREATE OR REPLACE FUNCTION getRankSem(IN semesterId INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS INTEGER AS $$
DECLARE
    student_avg FLOAT;
    rank INTEGER;
BEGIN
    -- Obtenir la moyenne de l'étudiant pour le semestre donné
    SELECT getSemMoy(semesterId, studentId, yearId) INTO student_avg;

    -- Calculer le rang de l'étudiant en fonction de sa moyenne par rapport aux autres étudiants
    SELECT COUNT(*) + 1 INTO rank
    FROM (
        SELECT DISTINCT(etdId), getSemMoy(semesterId, etdId, yearId) AS avg
        FROM   Moyenne m JOIN CompMod co ON co.modId = m.modId JOIN
		       Competence c ON c.compId = co.compId
        WHERE  semId = semesterId AND anneeId = yearId
    ) AS students_avg
    WHERE avg > student_avg OR (avg = student_avg AND etdId < studentId);

    RETURN rank;
END;
$$ LANGUAGE plpgsql;



-- get rank comp

CREATE OR REPLACE FUNCTION getRankComp(IN compIdd INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS INTEGER AS $$
DECLARE
    student_avg FLOAT;
    rank INTEGER;
BEGIN
    -- Obtenir la moyenne de l'étudiant pour la compétence donnée
    SELECT getCompMoy(compIdd, compIdd, studentId, yearId) INTO student_avg;

    -- Calculer le rang de l'étudiant en fonction de sa moyenne par rapport aux autres étudiants
    SELECT COUNT(*) + 1 INTO rank
    FROM (
        SELECT DISTINCT(etdId), getCompMoy(compIdd, compIdd, etdId, yearId) AS avg
        FROM   AdmComp admc JOIN Competence c ON c.compId = admc.compId 
               JOIN CompMod co ON co.compId = c.compId
        WHERE  anneeId = yearId AND co.compId = compIdd
    ) AS students_avg
    WHERE avg > student_avg OR (avg = student_avg AND etdId < studentId);

    RETURN rank;
END;
$$ LANGUAGE plpgsql;




/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/*                                      A  CORRIGER                                       */
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/




CREATE OR REPLACE FUNCTION getRCUE(IN compId1 INTEGER, IN compId2 INTEGER, IN studentId INTEGER, IN yearId INTEGER)
RETURNS VARCHAR AS $$
DECLARE
	student_adm1 VARCHAR := NULL;
	student_adm2 VARCHAR := NULL;
BEGIN
	-- Getting the admi of the two last semester for one comp
	SELECT admc1.admi INTO student_adm1
	FROM AdmComp admc1
	JOIN Competence c1 ON c1.compId=admc1.compId
	WHERE admc1.compId = compId1 AND admc1.etdId = studentId AND
		  admc1.anneeId = yearId;

	SELECT admc2.admi INTO student_adm2
	FROM AdmComp admc2
	JOIN Competence c2 ON c2.compId=admc2.compId
	WHERE admc2.compId = compId2 AND admc2.etdId = studentId AND
		  admc2.anneeId = yearId;

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
