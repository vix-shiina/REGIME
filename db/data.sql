USE REGIME;

INSERT INTO UserType (UserType)
VALUES
('Utilisateur'),
('Admin');

INSERT INTO Genre (Genre)
VALUES
('Homme'),
('Femme'),
('Autre');

INSERT INTO USER (Nom, Prenom, Email, Password, UserTypeId, GenreId)
VALUES
('Admin1', 'JOHN', 'admin1@example.com', 'password1', 2, 3),
('Rasoanaivo', 'Miora', 'miora.rasoanaivo@example.com', 'password1', 1, 2),
('Rakoto', 'Andry', 'andry.rakoto@example.com', 'password1', 1, 1),
('Razafindrabe', 'Tahina', 'tahina.razafindrabe@example.com', 'password1', 1, 3),
('Randria', 'Feno', 'feno.randria@example.com', 'password1', 1, 1);

INSERT INTO TypeDeRegime (TypeDeRegime)
VALUES
('Prise de poids'),
('Perte de poids'),
('Maintien de la forme');

INSERT INTO REGIME (RegimeNom, TypeDeRegimeId, PrixJournaliere, EfficacitePoidsParSemaine)
VALUES
('Regime prise masse classique', 1, 12000.00, 1.10),
('Regime perte poids active', 2, 10000.00, 0.95),
('Regime maintien equilibre', 3, 8000.00, 0.55),
('Regime prise masse premium', 1, 15000.00, 1.30),
('Regime perte poids intensive', 2, 13000.00, 1.15);

INSERT INTO CompoRegime (RegimeId, Viande, Poisson, Volailles)
VALUES
(1, 45, 25, 30),
(2, 25, 45, 30),
(3, 30, 35, 35),
(4, 55, 20, 25),
(5, 20, 50, 30);

INSERT INTO TypeDeSport (TypeDeSport)
VALUES
('Prise de masse'),
('Perte de poids'),
('Maintien de la forme');

INSERT INTO SPORT (SportNom, TypeDeSportId, EfficacitePoidsParSceance)
VALUES
('Musculation', 1, 1.10),
('Course a pied', 2, 0.95),
('Natation', 3, 0.45),
('Crossfit', 1, 1.20),
('Cyclisme', 2, 0.90);

INSERT INTO Code (Code, Valeur, Actif)
VALUES
('ARIARY5000', 5000.00, 1),
('ARIARY10000', 10000.00, 1),
('ARIARY15000', 15000.00, 1),
('ARIARY20000', 20000.00, 1),
('ARIARY25000', 25000.00, 1),
('ARIARY30000', 30000.00, 1),
('ARIARY40000', 40000.00, 1),
('ARIARY50000', 50000.00, 1),
('ARIARY60000', 60000.00, 1),
('ARIARY75000', 75000.00, 1),
('ARIARY100000', 100000.00, 1),
('ARIARY125000', 125000.00, 1),
('ARIARY150000', 150000.00, 1),
('ARIARY200000', 200000.00, 1),
('ARIARY250000', 250000.00, 1);