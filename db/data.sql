USE REGIME;

INSERT INTO UserType (UserType)
VALUES
('Utilisateur'),
('Admin');

INSERT INTO USER (Nom, Prenom, Email, Password, UserTypeId, GenreId)
VALUES
('Admin1', 'JOHN', 'admin1@example.com', 'password1', 2, 3),
('User1', 'DOE', 'user1@example.com', 'password1', 1, 3);

INSERT INTO Genre (Genre)
VALUES
('Homme'),
('Femme'),
('Autre');

INSERT INTO TypeDeRegime (TypeDeRegime)
VALUES
('Prise de poids'),
('Perte de poids');

INSERT INTO REGIME (RegimeNom, TypeDeRegimeId, PrixJournaliere, EfficacitePoids)
VALUES
('Regime Viande-Volaille', 1, 2.99, 0.8),
('Regime Viande-Poisson', 1, 3.99, 0.85),
('Regime Volaille-Poisson', 1, 1.99, 0.75),
('Regime Mixte 1', 1, 1.99, 0.78),
('Regime Mixte 2', 2, 2.99, 0.9);

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

INSERT INTO SPORT (SportNom, TypeDeSportId, EfficacitePoids)
VALUES
('Musculation', 1, 0.9),
('Cardio', 2, 0.85),
('Yoga', 3, 0.0),
('CrossFit', 1, 0.88),
('Pilates', 3, 0.0);

INSERT INTO Code (Code, Valeur)
VALUES
('CODE1', 100.0),
('CODE2', 80.0),
('CODE3', 50.0),
('CODE4', 150.0),
('CODE5', 500.0),
('CODE6', 500.0),
('CODE7', 300.0),
('CODE8', 80.0),
('CODE9', 90.0),
('CODE10', 100.0),
('CODE11', 110.0),
('CODE12', 120.0),
('CODE13', 130.0),
('CODE14', 140.0),
('CODE15', 150.0);