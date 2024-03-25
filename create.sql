DROP TABLE Admission IF EXISTS;
DROP TABLE CompMod   IF EXISTS;

DROP TABLE Note       IF EXISTS;
DROP TABLE Competence IF EXISTS;

DROP TABLE Utilisateur IF EXISTS;
DROP TABLE Semestre    IF EXISTS;
DROP TABLE Etudiant    IF EXISTS;
DROP TABLE Module      IF EXISTS;


/*                           */
/* TABLE DE PREMIERE LIAISON */
/*                           */

CREATE TABLE Module 
(
	modId   INTEGER     PRIMARY KEY                                                     ,
	modLib  VARCHAR(50)                                                                 ,
	modType VARCHAR(11) NOT NULL CHECK (typeMod IN ('Ressource', 'Sae', 'Stage', 'PPP'))
);

CREATE TABLE Semestre
(
	semId INTEGER PRIMARY KEY
);

CREATE TABLE Etudiant
(
	etdId       INTEGER     PRIMARY KEY,
	etdCiv      VARCHAR(3)  CHECK (typeMod IN ('Ressource', 'Sae', 'Stage', 'PPP'))
	etdNom      VARCHAR(30) ,
	etdPrenom   VARCHAR(30) ,
	etdGroupeTP VARCHAR(10) ,
	etdGroupeTD VARCHAR(10) ,
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
	compId  INTEGER PRIMARY KEY  ,
	compLib VARCHAR (10) NOT NULL,
	semId   INTEGER REFERENCES Semestre(semId)
);

CREATE TABLE Note 
(
	noteId  INTEGER PRIMARY KEY,
	noteVal FLOAT   CHECK      (noteVal < 0),
	etdId   INTEGER REFERENCES Etudiant(etdId),
	modId   INTEGER REFERENCES Module  (modId)    
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