DELETE FROM AdmComp;
DELETE FROM AdmAnnee;
DELETE FROM CompMod;
DELETE FROM Moyenne;
DELETE FROM Competence;

DELETE FROM Module;
DELETE FROM Semestre;
DELETE FROM Etudiant;
DELETE FROM Annee;
DELETE FROM Utilisateur;

-- Réinitialiser la séquence pour modId à 0
ALTER SEQUENCE Module_modId_seq RESTART WITH 1;
UPDATE Module SET modId = nextval('Module_modId_seq');

ALTER SEQUENCE Semestre_semId_seq RESTART WITH 1;
UPDATE Semestre SET semId = nextval('Semestre_semId_seq');

ALTER SEQUENCE Competence_compId_seq RESTART WITH 1;
UPDATE Competence SET compId = nextval('Competence_compId_seq');

ALTER SEQUENCE Annee_anneeId_seq RESTART WITH 1;
UPDATE Annee SET anneeId = nextval('Annee_anneeId_seq');


INSERT INTO Competence (compCode, compLib, semId) 
VALUES 
	('C1 Sem1','C11', 1),
	('C2 Sem1','C12', 1),
	('C3 Sem1','C13', 1),

	('C4 Sem2','C24', 2), -- 4
	('C5 Sem2','C25', 2),
	('C3 Sem2','C23', 2),
	('C2 Sem2','C22', 2),

	('C1 Sem3','C31', 3), -- 8
	('C2 Sem3','C32', 3),
	('C3 Sem3','C33', 3),

	('C5 Sem4','C25', 4); -- 11
	

INSERT INTO Module (modId, modCat, modLib)
VALUES      ('R11','e','R1.01'),
			('R22','e','R2.02'),

			('R21','e','R2.01'),
			('R23','e','R2.03'),
			('R24','e','R2.04'),
			('R25','e','R2.05'),

			('R11','e','R1.02'),
			('R13','e','R1.03'),
			('R14','e','R1.04'),

			('R31','e','R3.01'),
			('R32','e','R3.02'),
			('R33','e','R3.03')
			
			('R42','e','R4.02');

INSERT INTO Annee (anneLib) VALUES 
	('2021-2022'), -- Année scolaire 2021-2022
	('2022-2023'); -- Année scolaire 2022-2023



INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;

INSERT INTO Etudiant (etdId, etdCiv, etdNom, etdPrenom, etdGroupeTP, etdGroupeTD, etdCursus, etdBonus, etdBac) 
VALUES 
	(1, 'M.',	 'Dupont', 	 'Jean',	 	'B', 'B1', 'S1 S2', 0, 'Bac S'),
	(2, 'Mme',	 'Durand', 	 'Marie',	 	'C', 'C1', 'S1 S2', 0.5, 'Bac ES'),
	(3, 'M.',	 'Lefebvre', 'Pierre', 		'C', 'C1', 'S1 S2', 1.2, 'Bac STI2D');
	(4, 'Mme',	 'Durand',   'Trinity',     'A', 'A2', 'S1 S2', 0.5, 'Bac ES'),
	(5, 'Mme',	 'Reau',     'Rodolf',      'A', 'A1', 'S1 S2', 0.5, 'Bac HES'),
	(6, 'Mme',	 'Tuturu',   'Titouan',     'C', 'C2', 'S1 S2', 0.5, 'Bac HES'),
	(7, 'Mme',	 'Yvelines', 'Jean-Michel', 'B', 'B2', 'S1 S2', 0.5, 'Bac HES'),
	(8, 'Mme',	 'Apa',      'Gnan',        'G', 'G1', 'S1 S2 S3 S4', 0.5, 'Bac HES'),
	(9, 'Mme',	 'Roulette', 'Robert',      'G', 'G1', 'S1 S2 S3 S4', 0.5, 'Bac HES'),
	(10, 'Mme',	 'Random',   'Marie',       'G', 'G2', 'S1 S2 S3 S4', 0.5, 'Bac HES'),
	(11, 'M.',	 'Lefebvre', 'Pierre',      'F', 'F2', 'S1 S2 S3 S4', 1.2, 'Bac STI2D');

INSERT INTO Utilisateur (userLogin, userPassword, userDroit) 
VALUES 
	('john_doe', 'password123', 1),
	('jane_smith', 'secret789', 2),
	('admin_user', 'adminpass', 1);

INSERT INTO Moyenne (noteVal, etdId, modId, anneeId) 
VALUES
	(14.5, 1, 'R11', 1),
	(16.8, 1, 'R22', 1),

	(17.2, 2, 'R11', 1),
	(17.2, 2, 'R11', 1),
	(15.9, 2, 'R22', 2),

	(18.3, 3, 'R11', 2),
	(18.3, 3, 'R11', 2),
	(19.1, 3, 'R22', 2);


INSERT INTO AdmComp (etdId, compId, anneeId, admi)
VALUES 
	(1, 1, 1, 'ADM'),   -- Jean Dupont est admis à la compétence C.A pour l'année 1
	(2, 1, 1, 'CMP'),   -- Marie Durand est complétée à la compétence C.A pour l'année 1
	(3, 2, 1, 'AJ'),    -- Pierre Lefebvre est ajourné à la compétence C.B pour l'année 1
	(1, 4, 2, 'ADM'),   -- Jean Dupont est admis à la compétence C.D pour l'année 2
	(2, 5, 2, 'ADSUP'); -- Marie Durand est admis avec mention à la compétence C.E pour l'année 2

-- Remplir la table AdmAnnee
INSERT INTO AdmAnnee (etdId, anneeId, admi)
VALUES 
	(1, 1, 'ADM'),   -- Jean Dupont est admis pour l'année 1
	(2, 1, 'PASD'),  -- Marie Durand est passé pour l'année 1
	(3, 1, 'RED'),   -- Pierre Lefebvre est redoublant pour l'année 1
	(1, 2, 'ADM'),   -- Jean Dupont est admis pour l'année 2
	(2, 2, 'NAR'),   -- Marie Durand est non admis pour l'année 2
	(3, 2, 'NR');   -- Marie Durand est non admis pour l'année 2



-- Insertion des données dans la table CompMod
INSERT INTO CompMod (compId, modId, modCoef) VALUES
	(1, 'R11', 1.5), -- sem 1
	(1, 'R13', 1.2),

	(2, 'R13', 1.0), 
	(2, 'R14', 1.3),

	(3, 'R13', 0.8), 
	(3, 'R14', 1.0),

	(4, 'R22', 1.2), -- sem 2
	(4, 'R24', 1.5),

	(5, 'R25', 1.0), 
	(5, 'R24', 1.1),

	(6, 'R22', 0.9), 
	(6, 'R24', 0.7),

	(7, 'R23', 1.7),
	(7, 'R22', 0.7),
	(7, 'R24', 1.5),

	(8, 'R32', 1.0), -- sem 3
	(8, 'R31', 0.7),

	(9, 'R32', 1.2),
	(9, 'R33', 0.7),

	(10, 'R33', 1.5),
	(10, 'R31', 0.7),

	(11, 'R42', 1.0); -- sem 4
	