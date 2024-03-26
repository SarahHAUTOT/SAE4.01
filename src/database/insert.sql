DELETE FROM Module;
DELETE FROM Semestre;
DELETE FROM Etudiant;
DELETE FROM Utilisateur;
DELETE FROM Competence;
DELETE FROM Moyenne;

-- Réinitialiser la séquence pour modId à 0
ALTER SEQUENCE Module_modId_seq RESTART WITH 1;
UPDATE Module SET modId = nextval('Module_modId_seq');

ALTER SEQUENCE Semestre_semId_seq RESTART WITH 1;
UPDATE Semestre SET semId = nextval('Semestre_semId_seq');

ALTER SEQUENCE Competence_compId_seq RESTART WITH 1;
UPDATE Competence SET compId = nextval('Competence_compId_seq');


INSERT INTO Module (modLib)
VALUES      ('R1.01'),
            ('R2.02');


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


INSERT INTO Competence (compLib, semId) 
VALUES 
    ('C.A', 1),
    ('C.B', 1),
    ('C.C', 1),
    ('C.D', 2),
    ('C.E', 2),
    ('C.F', 2);


INSERT INTO Moyenne (noteVal, etdId, modId) 
VALUES (14.5, 1, 8),
       (16.8, 1, 9),
       (17.2, 2, 8),
       (15.9, 2, 9),
       (18.3, 3, 8),
       (19.1, 3, 9);


