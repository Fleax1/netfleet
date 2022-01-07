# netfleet
 
- Cloner le repository\
- Executer la commande ```composer install```
- dans le fichier .env, éditer les informations de connexion à la base de donnée
- Créez une base de donnée nommée **netfleet**
- Exécutez ensuite la commande ``` php bin/console doctrine:migrations:migrate```
- Lancez votre serveur local avec la commande ```symfony server:start```

# Routes
- **/create** : Création d'un nouveau média en renseignant les champs (JSON) : "name", "synopsis", "type" et "imageurl"
- **/getall** : retoune la liste des médias en base de donnée
- **/get/ID** : précisez l'ID du média recherché. La route retourne les infos de ce dernier
