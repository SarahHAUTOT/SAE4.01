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


INSERT INTO Module (modCat, modLib)
VALUES      ('e','R1.01'),
            ('e','R2.02');

INSERT INTO Annee (anneLib) VALUES 
    ('2021-2022'), -- Année scolaire 2021-2022
    ('2022-2023'); -- Année scolaire 2022-2023



INSERT INTO Semestre DEFAULT VALUES;
INSERT INTO Semestre DEFAULT VALUES;


INSERT INTO Etudiant (etdId, etdCiv, etdNom, etdPrenom, etdGroupeTP, etdGroupeTD, etdParcours, etdBonus, etdBac) 
VALUES 
    (1, 'M.', 'Dupont', 'Jean', 'Groupe1', 'TD1', 'Mathématiques', 0, 'Bac S'),
    (2, 'Mme', 'Durand', 'Marie', 'Groupe2', 'TD2', 'Biologie', 0.5, 'Bac ES'),
    (3, 'M.', 'Lefebvre', 'Pierre', 'Groupe3', 'TD3', 'Informatique', 1.2, 'Bac STI2D');


INSERT INTO Utilisateur (userLogin, userPassword, userDroit) 
VALUES 
    ('john_doe', 'password123', 1),
    ('jane_smith', 'secret789', 2),
    ('admin_user', 'adminpass', 1);


INSERT INTO Competence (compCode, compLib, semId) 
VALUES 
    ('C.A','C.A', 1),
    ('C.B','C.B', 1),
    ('C.C','C.C', 1),
    ('C.D','C.D', 2),
    ('C.E','C.E', 2),
    ('C.F','C.F', 2);


INSERT INTO Moyenne (noteVal, etdId, modId, anneeId) 
VALUES (14.5, 1, 1, 1),
       (16.8, 1, 2, 1),
       (17.2, 2, 1, 1),
       (15.9, 2, 2, 2),
       (18.3, 3, 1, 2),
       (19.1, 3, 2, 2);


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
    (1, 1, 1.5),  -- Compétence C.A pour le module R1.01 avec un coefficient de 1.5
    (1, 2, 1.2),  -- Compétence C.A pour le module R2.02 avec un coefficient de 1.2
    (2, 1, 1.0),  -- Compétence C.B pour le module R1.01 avec un coefficient de 1.0
    (2, 2, 1.3),  -- Compétence C.B pour le module R2.02 avec un coefficient de 1.3
    (3, 1, 0.8),  -- Compétence C.C pour le module R1.01 avec un coefficient de 0.8
    (3, 2, 1.0),  -- Compétence C.C pour le module R2.02 avec un coefficient de 1.0
    (4, 1, 1.2),  -- Compétence C.D pour le module R1.01 avec un coefficient de 1.2
    (4, 2, 1.5),  -- Compétence C.D pour le module R2.02 avec un coefficient de 1.5
    (5, 1, 1.0),  -- Compétence C.E pour le module R1.01 avec un coefficient de 1.0
    (5, 2, 1.1),  -- Compétence C.E pour le module R2.02 avec un coefficient de 1.1
    (6, 1, 0.9),  -- Compétence C.F pour le module R1.01 avec un coefficient de 0.9
    (6, 2, 0.7);  -- Compétence C.F pour le module R2.02 avec un coefficient de 0.7