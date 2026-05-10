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
('User1', 'DOE', 'user1@example.com', 'password1', 1, 3);

INSERT INTO TypeDeRegime (TypeDeRegime)
VALUES
('Prise de poids'),
('Perte de poids');

INSERT INTO REGIME (RegimeNom, TypeDeRegimeId, PrixJournaliere, EfficacitePoidsParSemaine)
VALUES
('Regime Viande-Volaille', 1, 7000.00, 0.8),
('Regime Viande-Poisson', 1, 7500.00, 0.85),
('Regime Volaille-Poisson', 1, 6000.00, 0.75),
('Regime Mixte 1', 1, 3500.00, 0.78),
('Regime Mixte 2', 2, 7000.00, 0.9);

INSERT INTO CompoRegime (RegimeId, Viande, Poisson, Volailles)
VALUES
(1, 50, 0, 50),
(2, 50, 50, 0),
(3, 0, 50, 50),
(4, 25, 25, 50),
(5, 33, 33, 34);

INSERT INTO TypeDeSport (TypeDeSport)
VALUES
('Prise de masse'),
('Perte de poids'),
('Maintien de la forme');

INSERT INTO SPORT (SportNom, TypeDeSportId, EfficacitePoidsParSceance)
VALUES
('Musculation', 1, 0.9),
('Cardio', 2, 0.85),
('Yoga', 3, 0.0),
('CrossFit', 1, 0.88),
('Pilates', 3, 0.0);

INSERT INTO Code (Code, Valeur)
VALUES
('CODE1', 100000.0),
('CODE2', 8000.0),
('CODE3', 5000.0),
('CODE4', 10050.0),
('CODE5', 5000.0),
('CODE6', 5000.0),
('CODE7', 3000.0),
('CODE8', 8500.0),
('CODE9', 9050.0),
('CODE10', 5000.0),
('CODE11', 10100.0),
('CODE12', 12000.0),
('CODE13', 13000.0),
('CODE14', 14000.0),
('CODE15', 15000.0);