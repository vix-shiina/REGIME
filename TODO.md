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



