DROP TABLE IF EXISTS Admission;
DROP TABLE IF EXISTS CompMod  ;

DROP TABLE IF EXISTS Moyenne   ;
DROP TABLE IF EXISTS Competence;

DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Semestre   ;
DROP TABLE IF EXISTS Etudiant   ;
DROP TABLE IF EXISTS Module     ;


/*                           */
/* TABLE DE PREMIERE LIAISON */
/*                           */

CREATE TABLE Module 
(
	modId   SERIAL      PRIMARY KEY,
	modLib  VARCHAR(50)                                                                 
);

CREATE TABLE Semestre
(
	semId SERIAL  PRIMARY KEY
);

CREATE TABLE Etudiant
(
	etdId       INTEGER     PRIMARY KEY,
	etdCiv      VARCHAR(3)  CHECK (etdCiv IN ('M.', 'Mme')),
	etdNom      VARCHAR(30) ,
	etdPrenom   VARCHAR(30) ,
	etdGroupeTP VARCHAR(10) ,
	etdGroupeTD VARCHAR(10) ,
	etdParcours VARCHAR(30) ,
	etdBonus    FLOAT       CHECK (etdBonus >= 0) DEFAULT 0,
	etdBac      VARCHAR(20)
);

CREATE TABLE Utilisateur
(
	userLogin    VARCHAR(30) NOT NULL,
	userPassword VARCHAR(30) NOT NULL,
	userDroit    INTEGER CHECK (userDroit BETWEEN 1 AND 2),
	PRIMARY KEY (userLogin, userPassword)
);




/*                           */
/* TABLE DE PREMIERE LIAISON */
/*                           */

CREATE TABLE Competence 
(
	compId  SERIAL  PRIMARY KEY  ,
	compLib VARCHAR (10) NOT NULL,
	semId   INTEGER REFERENCES Semestre(semId)
);

CREATE TABLE Moyenne 
(
	noteVal FLOAT   CHECK      (noteVal > 0),
	etdId   INTEGER REFERENCES Etudiant(etdId),
	modId   INTEGER REFERENCES Module  (modId),
	PRIMARY KEY (etdId, modId)
);




/*                           */
/* TABLE DE DEUXIEME LIAISON */
/*                           */

CREATE TABLE CompMod 
(
	compId  INTEGER REFERENCES Competence(compId),
	modId   INTEGER REFERENCES Module  (modId)   ,
	modCoef FLOAT   CHECK      (modCoef > 0)
);

CREATE TABLE Admission 
(
	compId  INTEGER REFERENCES Competence(compId),
	etdId   INTEGER REFERENCES Etudiant(etdId),
	admi    VARCHAR(5)         CHECK (admi IN ('ADM','CMP','AJ','ADSUP'))
);