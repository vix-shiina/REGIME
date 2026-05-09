## TABLES
- Usertype : id, type
- Genre : id, type
- User : id, nom, prenom, password, email, idGenre
- User-info : idUser, age, poids, taille
- Evolution : idUser, poids, taille, Date
- TypeRegime : id, action
- Regime : id, nom,  idType, prix, efficacite
- UserRegime : idUser, idRegime
- Code : id, code, valeur
- TypeSport : id, action
- Sport : id, idAction, nom, action
- RegimeComponente : idRegime, viande, poisson, volaille

## PAGES
### Vue client
- inscription + login
- Dashboard
- Page suggestion regime + Activite sportive 
- Page profil : - info
                - solde + ajouter solde
                - Completion du profil + choix objectif

### Vue admin
- CRUD et gestion 

Mila manao page ray marina 


bouton commencer un regime

Suggestion regime
Entrer taille > entrer poids > calcule IMC > mode rapide - normal - lente > proposition de regime

Regime personnalisé + sport
Entrer poids > enter taille > ++ ou -- > filtrage des par type de regime > ajouter un sport ? > Selectionner sport à pratiquer par jour


///coter admin
    -gerer regimes
        -[wip]models
            -[ok]regimemodel.php
                -[ok]CRUD
            -[]clientmodel.php
                -[]findAll
            -[wip]sportmodel.php
                -[ok]CRUD
            -[wip]CodeModel.php
                -[ok] CRUD
        -[]views
            -[]cards liens vers chaque page
        -[ok]Controller
            -[]Auth.php
                -[]Appel CRUD a chaque foctionnalite
        -[]routes
            -[] Attribuer une route a chaque fonctionnalites