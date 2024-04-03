DROP TABLE IF EXISTS AdmAnnee ;
DROP TABLE IF EXISTS AdmComp  ;
DROP TABLE IF EXISTS CompMod  ;

DROP TABLE IF EXISTS Moyenne   ;
DROP TABLE IF EXISTS Competence;

DROP TABLE IF EXISTS Export     ;
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Semestre   ;
DROP TABLE IF EXISTS Annee      ;
DROP TABLE IF EXISTS Etudiant   ;
DROP TABLE IF EXISTS Module     ;


/*                           */
/* TABLE DE PREMIERE LIAISON */
/*                           */
CREATE TABLE Export 
(
	exportId     SERIAL      PRIMARY KEY,
	exportType   VARCHAR(2)  CHECK (exportType IN ('PE', 'CO', 'JU')),
	exportChemin VARCHAR(50) NOT NULL
);

CREATE TABLE Module 
(
	modId   VARCHAR(10) PRIMARY KEY,
	modCode VARCHAR(10) ,
	modCat  VARCHAR(20) NOT NULL,
	modLib  VARCHAR(255) NOT NULL
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
	etdCursus   VARCHAR(30) ,
	etdBonus    FLOAT       CHECK (etdBonus >= 0) DEFAULT 0,
	etdAbs      INTEGER	    CHECK (etdAbs   >= 0) DEFAULT 0,
	etdBac      VARCHAR(20)
);

CREATE TABLE Utilisateur
(
	userLogin    VARCHAR(30) NOT NULL,
	userPassword VARCHAR(30) NOT NULL,
	userDroit    INTEGER CHECK (userDroit BETWEEN 1 AND 2),
	PRIMARY KEY (userLogin, userPassword)
);

CREATE TABLE Annee
(
	anneeId   SERIAL      NOT NULL,
	anneLib   VARCHAR(30) NOT NULL,
	PRIMARY KEY (anneeId)
);




/*                           */
/* TABLE DE PREMIERE LIAISON */
/*                           */

CREATE TABLE Competence 
(
	compId   SERIAL  PRIMARY KEY  ,
	compCode VARCHAR (10) NOT NULL,
	compLib  VARCHAR (255) NOT NULL,
	semId    INTEGER REFERENCES Semestre(semId)
);

CREATE TABLE Moyenne 
(
	noteVal FLOAT       CHECK      (noteVal >= 0),
	etdId   INTEGER     REFERENCES Etudiant(etdId),
	modId   VARCHAR(10) REFERENCES Module  (modId),
	anneeId INTEGER     REFERENCES Annee   (anneeId),
	PRIMARY KEY (etdId, modId, anneeId)
);


/*                           */
/* TABLE DE DEUXIEME LIAISON */
/*                           */

CREATE TABLE CompMod 
(
	compId  INTEGER     REFERENCES Competence(compId),
	modId   VARCHAR(10) REFERENCES Module    (modId) ,
	modCoef FLOAT       CHECK      (modCoef > 0)     ,
	PRIMARY KEY (compId, modId)
);

CREATE TABLE AdmComp
(
	etdId   INTEGER REFERENCES Etudiant(etdId),
	compId  INTEGER REFERENCES Competence(compId),
	anneeId INTEGER REFERENCES Annee(anneeId),
	admi    VARCHAR(5) CHECK (admi IN ('ADM','CMP','AJ','ADSUP','NR')) DEFAULT 'NR',
	PRIMARY KEY (anneeId,compId,etdId)
);

CREATE TABLE AdmAnnee
(
	etdId   INTEGER REFERENCES Etudiant(etdId),
	anneeId INTEGER REFERENCES Annee(anneeId),
	admi    VARCHAR(5) CHECK (admi IN ('ADM','PASD','RED','NAR', 'ABL', 'NR' )) DEFAULT 'NR',
	PRIMARY KEY (anneeId,etdId)
);


-- Les 6 semestres
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;


INSERT INTO Module (modId, modCode, modCat, modLib) 
VALUES
    ('R101', 'BINR101', 'NR', 'Initiation au développement'),
    ('R102', 'BINR102', 'NR', 'Développement d''interfaces web'),
    ('R103', 'BINR103', 'NR', 'Introduction à l''architecture des ordinateurs'),
    ('R104', 'BINR104', 'NR', 'Introduction aux systèmes d''exploitation'),
    ('R105', 'BINR105', 'NR', 'Introduction aux bases de données et SQL'),
    ('R106', 'BINR106', 'Math', 'Mathématiques discrètes'),
    ('R107', 'BINR107', 'Math', 'Outils mathématiques fondamentaux'),
    ('R108', 'BINR108', 'NR', 'Introduction à la gestion des organisations'),
    ('R109', 'BINR109', 'NR', 'Introduction à l''économie durable et numérique'),
    ('R110', 'BINR110', 'NR', 'Anglais'),
    ('R111', 'BINR111', 'NR', 'Bases de la communication'),
    ('R112', 'BINR112', 'NR', 'Projet professionnel et personnel'),
    ('S101', 'BINS101', 'NR', 'Implémentation d''un besoin client'),
    ('S102', 'BINS102', 'NR', 'Comparaison d''approches algorithmiques'),
    ('S103', 'BINS103', 'NR', 'Installation d''un poste pour le développement'),
    ('S104', 'BINS104', 'NR', 'Création d''une base de données'),
    ('S105', 'BINS105', 'NR', 'Recueil de besoins'),
    ('S106', 'BINS106', 'NR', 'Découverte de l''environnement économique et écologique'),
    ('R201', 'BINR201', 'NR', 'Développement orienté objets'),
    ('R202', 'BINR202', 'NR', 'Développement d''applications avec IHM'),
    ('R203', 'BINR203', 'NR', 'Qualité de développement'),
    ('R204', 'BINR204', 'NR', 'Communication fonctionnement bas niveau'),
    ('R205', 'BINR205', 'NR', 'Introduction aux services réseaux'),
    ('R206', 'BINR206', 'NR', 'Exploitation d''une base de données'),
    ('R207', 'BINR207', 'Math', 'Graphes'),
    ('R208', 'BINR208', 'Math', 'Outils numériques pour les statistiques descriptives'),
    ('R209', 'BINR209', 'Math', 'Méthodes numériques'),
    ('R210', 'BINR210', 'NR', 'Introduction à la gestion des systèmes d''information'),
    ('R211', 'BINR211', 'NR', 'Introduction au droit'),
    ('R212', 'BINR212', 'NR', 'Anglais'),
    ('R213', 'BINR213', 'NR', 'Communication technique'),
    ('R214', 'BINR214', 'NR', 'Projet professionnel et personnel'),
    ('S201', 'BINS201', 'NR', 'Développement d''une application'),
    ('S202', 'BINS202', 'NR', 'Exploration algorithmique d''un problème'),
    ('S203', 'BINS203', 'NR', 'Installation de service réseau'),
    ('S204', 'BINS204', 'NR', 'Exploitation d''une base de données'),
    ('S205', 'BINS205', 'NR', 'Gestion d''un projet'),
    ('S206', 'BINS206', 'NR', 'Organisation d''un travail d''équipe'),
    ('P201', 'BINP201', 'NR', 'Portfolio'),
    ('R301', 'BINR301', 'NR', 'Développement web'),
    ('R302', 'BINR302', 'NR', 'Développement efficace'),
    ('R303', 'BINR303', 'NR', 'Analyse'),
    ('R304', 'BINR304', 'NR', 'Qualité de développement'),
    ('R305', 'BINR305', 'NR', 'Programmation système'),
    ('R306', 'BINR306', 'NR', 'Architecture des réseaux'),
    ('R307', 'BINR307', 'NR', 'SQL dans un langage de programmation'),
    ('R308', 'BINR308', 'NR', 'Probabilités'),
    ('R309', 'BINR309', 'Math', 'Cryptographie et sécurité'),
    ('R310', 'BINR310', 'NR', 'Management des systèmes d''information'),
    ('R311', 'BINR311', 'NR', 'Droits des contrats et du numérique'),
    ('R312', 'BINR312', 'NR', 'Anglais'),
    ('R313', 'BINR313', 'NR', 'Communication professionnelle'),
    ('R314', 'BINR314', 'NR', 'PPP'),
    ('S301', 'BINS301', 'NR', 'Développement d''une application'),
    ('P301', 'BINP301', 'NR', 'Portfolio'),
    ('R401', 'BINR401', 'NR', 'Architecture logicielle'),
    ('R402', 'BINR402', 'NR', 'Qualité de développement'),
    ('R403', 'BINR403', 'NR', 'Qualité et au-delà du relationnel'),
    ('R404', 'BINR404', 'NR', 'Méthodes d''optimisation'),
    ('R405', 'BINR405', 'NR', 'Anglais'),
    ('R406', 'BINR406', 'NR', 'Communication interne'),
    ('R407', 'BINR407', 'NR', 'PPP'),
    ('R408', 'BINR408', 'NR', 'Virtualisation'),
    ('R409', 'BINR409', 'NR', 'Management avancé SI'),
    ('R410', 'BINR410', 'NR', 'Complément web'),
    ('R411', 'BINR411', 'NR', 'Développement mobile'),
    ('R412', 'BINR412', 'Math', 'Automates'),
    ('S401', 'BINS401', 'NR', 'Développement d''une application'),
    ('S411', 'BINS411', 'NR', 'Stage'),
    ('R501', 'BINR501', 'NR', 'Initiation managment équipe informatique'),
    ('R502', 'BINR502', 'NR', 'PPP'),
    ('R503', 'BINR503', 'NR', 'Politique de communication'),
    ('R504', 'BINR504', 'NR', 'Qualité algorithmique'),
    ('R505', 'BINR505', 'NR', 'Programmation avancée'),
    ('R506', 'BINR506', 'NR', 'Sensibilisation à la programmation multimedia'),
    ('R507', 'BINR507', 'NR', 'Automatisation de la chaine de production'),
    ('R508', 'BINR508', 'NR', 'Qualité de développement'),
    ('R509', 'BINR509', 'NR', 'Virtualisation avancée'),
    ('R510', 'BINR510', 'NR', 'Nouveaux paradigmes de base de données'),
    ('R511', 'BINR511', 'NR', 'Méthodes d''optimisation pour l''aide à la décision'),
    ('R512', 'BINR512', 'Math', 'Modélisations mathématiques'),
    ('R513', 'BINR513', 'NR', 'Economie durable et numérique'),
    ('R514', 'BINR514', 'NR', 'Anglais'),
    ('S501', 'BINS501', 'NR', 'Développement avancé'),
    ('P501', 'BINP501', 'NR', 'Portfolio');


INSERT INTO Competence (compId, compCode, compLib, semId) 
VALUES
    (11, 'BIN11', 'UE 1. Réaliser', 1),
    (21, 'BIN21', 'UE 1. Réaliser', 2),
    (31, 'BIN31', 'UE 1. Réaliser', 3),
    (41, 'BIN41', 'UE 1. Réaliser', 4),
    (51, 'BIN51', 'UE 1. Réaliser', 5),
    (61, 'BIN61', 'UE 1. Réaliser', 6),
    (12, 'BIN12', 'UE 2. Optimiser', 1),
    (22, 'BIN22', 'UE 2. Optimiser', 2),
    (32, 'BIN32', 'UE 2. Optimiser', 3),
    (42, 'BIN42', 'UE 2. Optimiser', 4),
    (52, 'BIN52', 'UE 2. Optimiser', 5),
    (62, 'BIN62', 'UE 2. Optimiser', 6),
    (13, 'BIN13', 'UE 3. Administrer', 1),
    (23, 'BIN23', 'UE 3. Administrer', 2),
    (33, 'BIN33', 'UE 3. Administrer', 3),
    (43, 'BIN43', 'UE 3. Administrer', 4),
    (53, 'BIN53', 'UE 3. Administrer', 5),
    (63, 'BIN63', 'UE 3. Administrer', 6),
    (14, 'BIN14', 'UE 4. Gérer', 1),
    (24, 'BIN24', 'UE 4. Gérer', 2),
    (34, 'BIN34', 'UE 4. Gérer', 3),
    (44, 'BIN44', 'UE 4. Gérer', 4),
    (54, 'BIN54', 'UE 4. Gérer', 5),
    (64, 'BIN64', 'UE 4. Gérer', 6),
    (15, 'BIN15', 'UE 5. Conduire', 1),
    (25, 'BIN25', 'UE 5. Conduire', 2),
    (35, 'BIN35', 'UE 5. Conduire', 3),
    (45, 'BIN45', 'UE 5. Conduire', 4),
    (55, 'BIN55', 'UE 5. Conduire', 5),
    (65, 'BIN65', 'UE 5. Conduire', 6),
    (16, 'BIN16', 'UE 6. Collaborer', 1),
    (26, 'BIN26', 'UE 6. Collaborer', 2),
    (36, 'BIN36', 'UE 6. Collaborer', 3),
    (46, 'BIN46', 'UE 6. Collaborer', 4),
    (56, 'BIN56', 'UE 6. Collaborer', 5),
    (66, 'BIN66', 'UE 6. Collaborer', 6);


INSERT INTO CompMod (compId, modId, modCoef)
VALUES
    (11, 'R101', 46),
    (21, 'R101', 30),
    (51, 'R101', 5),
    (61, 'R101', 4),
    (11, 'R102', 8),
    (51, 'R102', 13),
    (61, 'R102', 5),
    (31, 'R103', 20),
    (31, 'R104', 20),
    (41, 'R105', 36),
    (51, 'R105', 4),
    (21, 'R106', 10),
    (41, 'R106', 18),
    (21, 'R107', 20),
    (51, 'R108', 23),
    (61, 'R108', 9),
    (41, 'R109', 6),
    (61, 'R109', 12),
    (11, 'R110', 6),
    (31, 'R110', 12),
    (61, 'R110', 12),
    (31, 'R111', 8),
    (51, 'R111', 15),
    (61, 'R111', 12),
    (61, 'R112', 6),
    (11, 'S101', 40),
    (21, 'S102', 40),
    (31, 'S103', 40),
    (41, 'S104', 40),
    (51, 'S105', 40),
    (61, 'S106', 40),
    (12, 'R201', 23),
    (22, 'R201', 27),
    (32, 'R201', 12),
    (12, 'R202', 21),
    (42, 'R202', 5),
    (52, 'R202', 5),
    (62, 'R202', 4),
    (12, 'R203', 12),
    (52, 'R203', 6),
    (32, 'R204', 17),
    (32, 'R205', 14),
    (42, 'R206', 30),
    (22, 'R207', 20),
    (32, 'R207', 2),
    (52, 'R207', 6),
    (42, 'R208', 10),
    (22, 'R209', 10),
    (32, 'R209', 3),
    (42, 'R210', 9),
    (52, 'R210', 28),
    (62, 'R211', 17),
    (32, 'R212', 6),
    (42, 'R212', 6),
    (52, 'R212', 6),
    (62, 'R212', 17),
    (12, 'R213', 4),
    (22, 'R213', 3),
    (32, 'R213', 6),
    (52, 'R213', 9),
    (62, 'R213', 16),
    (62, 'R214', 6),
    (12, 'S201', 38),
    (22, 'S202', 38),
    (32, 'S203', 38),
    (42, 'S204', 38),
    (52, 'S205', 38),
    (62, 'S206', 38),
    (12, 'P201', 2),
    (22, 'P201', 2),
    (32, 'P201', 2),
    (42, 'P201', 2),
    (52, 'P201', 2),
    (62, 'P201', 2),
    (13, 'R301', 15),
    (23, 'R301', 5),
    (33, 'R301', 5),
    (43, 'R301', 10),
    (13, 'R302', 12),
    (23, 'R302', 15),
    (53, 'R302', 8),
    (13, 'R303', 8),
    (23, 'R303', 4),
    (53, 'R303', 8),
    (13, 'R304', 15),
    (53, 'R304', 8),
    (63, 'R304', 5),
    (33, 'R305', 22),
    (23, 'R306', 5),
    (33, 'R306', 14),
    (33, 'R307', 6),
    (43, 'R307', 25),
    (23, 'R308', 20),
    (43, 'R308', 5),
    (23, 'R309', 6),
    (33, 'R309', 8),
    (43, 'R309', 5),
    (63, 'R309', 4),
    (43, 'R310', 10),
    (53, 'R310', 10),
    (63, 'R310', 12),
    (13, 'R311', 8),
    (43, 'R311', 5),
    (53, 'R311', 10),
    (23, 'R312', 5),
    (33, 'R312', 5),
    (53, 'R312', 7),
    (63, 'R312', 8),
    (13, 'R313', 2),
    (53, 'R313', 9),
    (63, 'R313', 16),
    (63, 'R314', 15),
    (13, 'S301', 40),
    (23, 'S301', 40),
    (33, 'S301', 40),
    (43, 'S301', 40),
    (53, 'S301', 40),
    (63, 'S301', 40),
    (14, 'R401', 16),
    (34, 'R401', 14),
    (64, 'R401', 4),
    (14, 'R402', 8),
    (54, 'R402', 10),
    (44, 'R403', 18),
    (24, 'R404', 12),
    (24, 'R405', 4),
    (64, 'R405', 13),
    (44, 'R406', 6),
    (64, 'R406', 13),
    (64, 'R407', 10),
    (34, 'R408', 22),
    (24, 'R409', 4),
    (54, 'R409', 18),
    (14, 'R410', 8),
    (24, 'R410', 4),
    (44, 'R410', 8),
    (54, 'R410', 4),
    (14, 'R411', 8),
    (24, 'R411', 4),
    (44, 'R411', 8),
    (54, 'R411', 8),
    (24, 'R412', 12),
    (34, 'R412', 4),
    (14, 'S401', 15),
    (24, 'S401', 15),
    (34, 'S401', 15),
    (44, 'S401', 15),
    (54, 'S401', 15),
    (64, 'S401', 15),
    (14, 'S411', 40),
    (24, 'S411', 40),
    (34, 'S411', 40),
    (44, 'S411', 40),
    (54, 'S411', 40),
    (64, 'S411', 40),
    (65, 'R501', 9),
    (65, 'R502', 6),
    (65, 'R503', 13),
    (15, 'R504', 2),
    (25, 'R504', 6),
    (15, 'R505', 9),
    (25, 'R505', 7),
    (15, 'R506', 2),
    (25, 'R506', 2),
    (65, 'R506', 2),
    (15, 'R507', 6),
    (65, 'R507', 2),
    (15, 'R508', 7),
    (25, 'R508', 5),
    (15, 'R509', 7),
    (25, 'R509', 2),
    (15, 'R510', 12),
    (25, 'R510', 4),
    (25, 'R511', 7),
    (25, 'R512', 13),
    (15, 'R513', 2),
    (65, 'R513', 5),
    (15, 'R514', 3),
    (25, 'R514', 4),
    (65, 'R514', 13),
    (15, 'S501', 50),
    (25, 'S501', 50),
    (65, 'S501', 50);



INSERT INTO Utilisateur VALUES ( 'admin', 'admin', 2),
                               (  'user',  'user', 1);