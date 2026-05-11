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
('Perte de poids'),
('Maintien de la forme');

INSERT INTO REGIME (RegimeNom, TypeDeRegimeId, PrixJournaliere, EfficacitePoidsParSemaine)
VALUES
('Régime Keto', 2, 8500.00, 1.2),
('Régime Paléo', 2, 9000.00, 1.0),
('Régime Méditerranéen', 3, 6500.00, 0.6),
('Régime Vegan Équilibré', 2, 5500.00, 0.8),
('Régime Protéiné +', 1, 9500.00, 1.1),
('Régime Low Carb', 2, 7500.00, 0.95),
('Régime DASH', 3, 6000.00, 0.7),
('Régime Musculation', 1, 8000.00, 1.3),
('Régime Hyperprotéiné', 1, 10000.00, 1.4),
('Régime Équilibré', 3, 5000.00, 0.5);

INSERT INTO CompoRegime (RegimeId, Viande, Poisson, Volailles)
VALUES
(1, 40, 40, 20),
(2, 35, 35, 30),
(3, 20, 40, 40),
(4, 0, 30, 70),
(5, 50, 25, 25),
(6, 30, 45, 25),
(7, 25, 40, 35),
(8, 60, 20, 20),
(9, 55, 25, 20),
(10, 30, 30, 40);

INSERT INTO TypeDeSport (TypeDeSport)
VALUES
('Prise de masse'),
('Perte de poids'),
('Maintien de la forme');

INSERT INTO SPORT (SportNom, TypeDeSportId, EfficacitePoidsParSceance)
VALUES
('Musculation', 1, 1.1),
('Cardio Intensif', 2, 1.0),
('Yoga', 3, 0.2),
('Running', 2, 0.95),
('Natation', 2, 1.05),
('CrossFit', 1, 1.15),
('Pilates', 3, 0.3),
('Boxe', 2, 1.2),
('Cyclisme', 2, 0.9),
('Danse', 3, 0.5);

INSERT INTO Code (Code, Valeur, Actif)
VALUES
('REGIME2024', 50000.0, 1),
('WELCOME500', 500000.0, 1),
('STARTER100', 100000.0, 1),
('PROMO1M', 1000000.0, 1),
('BOOST50K', 50000.0, 1),
('HEALTH25K', 25000.0, 1),
('FITNESS10K', 10000.0, 1),
('MEGA300K', 300000.0, 1),
('SUMMER75K', 75000.0, 1),
('GOLD200K', 200000.0, 1),
('SILVER150K', 150000.0, 1),
('BRONZE75K', 75000.0, 1),
('LUCKY500K', 500000.0, 1),
('DAILY25K', 25000.0, 1),
('PLATINUM400K', 400000.0, 1);