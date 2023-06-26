# Test BTP Management sous Symfony 4 - Guide d'installation

Ce projet Symfony 4 met en œuvre un système de gestion des utilisateurs, des chantiers et des pointages. Il permet de réaliser des opérations CRUD (Create, Read, Update, Delete) sur les entités Utilisateur et Chantier, ainsi que de créer des pointages liés à un utilisateur et un chantier donnés.

### Prérequis

Avant d'installer le projet, assurez-vous d'avoir les éléments suivants sur votre machine :

**PHP 7.2** ou une version ultérieure voir **8.1**

Composer **([https://getcomposer.org](https://getcomposer.org))**

Symfony CLI ([https://symfony.com/download](https://symfony.com/download))

MySQL ou un autre système de gestion de base de données pris en charge par Symfony 4
Installation 

Assurez-vous d'avoir l'extension MySQL activer sur la configuration PHP (php.ini)

Suivez ces étapes pour installer et exécuter le projet :

1. Clonez le référentiel GitHub dans le répertoire de votre choix :
`git clone https://github.com/yesitswalid/BTPManagement.git`


2. Accédez au répertoire du projet :
`cd BTPManagement`


3. Installez les dépendances PHP en exécutant la commande suivante :
    `composer install`


4. Configurez les informations de la base de données dans le fichier .env. Modifiez les lignes suivantes en fonction de votre configuration de base de données :
  ` DATABASE_URL=mysql://utilisateur:motdepasse@hote/nom_base_de_données`


5. Créez la base de données en exécutant la commande suivante : `symfony console doctrine:database:create`


6. Exécutez les migrations pour créer les tables de base de données : `symfony console doctrine:migrations:execute --up 'DoctrineMigrations\Version20230626094447'`


7. Lancez le serveur Symfony en utilisant la commande suivante :
`symfony server:start`


8. Accédez à l'application dans votre navigateur à l'adresse [http://localhost:8000](http://localhost:8000) (ou à l'adresse spécifiée par la commande server:run).


## Utilisation

Une fois l'application installée et lancée, vous pourrez accéder aux fonctionnalités suivantes :

* Page Utilisateurs : Vous pouvez effectuer des opérations CRUD sur les utilisateurs. Chaque utilisateur possède un nom, un prénom et un matricule.


* Page Chantiers : Vous pouvez effectuer des opérations CRUD sur les chantiers. 
Chaque chantier possède un nom, une adresse et une date de début. 
Vous pouvez également voir le nombre de personnes différentes ayant été pointées sur ce chantier, ainsi que le nombre total d'heures pointées sur ce chantier.


* Page Pointages : Vous pouvez afficher la liste des pointages existants et créer de nouveaux pointages liés à un utilisateur et un chantier spécifiques. Chaque pointage comprend une date et une durée. 

    Des validations spécifiques sont appliquées lors de la création des pointages :

  * Un utilisateur ne peut pas être pointé deux fois le même jour sur le même chantier.
  * La somme des durées des pointages d'un utilisateur pour une semaine ne peut pas dépasser 35 heures.
  Les messages d'erreur correspondants seront affichés sur le formulaire au fur et à mesure de la saisie des données, dès qu'une contrainte sera atteinte.


### **Informations Complémentaires**

Les bibliothèques suivantes ont été utilisées pour créer les interfaces de ce test :

**Symfony Form** : Symfony Form est une librairie intégrée à Symfony qui permet de créer des formulaires HTML. Elle fournit une abstraction pour générer des formulaires à partir de vos entités et gérer leur validation. Elle facilite la création, la gestion et la manipulation des données saisies par les utilisateurs.

**Bootstrap** : Bootstrap est un framework CSS populaire qui offre une collection d'outils et de composants prêts à l'emploi pour le développement web. Il fournit une base solide pour le design et la mise en page des interfaces utilisateur, en utilisant des classes CSS prédéfinies et des scripts JavaScript. Bootstrap permet de créer rapidement des interfaces attrayantes et réactives.

Ces librairies ont été choisies pour les raisons suivantes :

**Symfony Form** facilite la création de formulaires en se basant sur les entités du projet. Elle offre une validation intégrée des données saisies, permettant ainsi de garantir l'intégrité des données lors de leur soumission.

**Bootstrap** offre un ensemble de composants et de classes CSS prédéfinis, ce qui permet d'accélérer le processus de développement en évitant de devoir écrire beaucoup de CSS personnalisé. De plus, il offre une grande flexibilité pour la création d'interfaces responsives et esthétiquement plaisantes.

En utilisant ces librairies, il est possible de créer rapidement des interfaces utilisateur interactives et réactives, tout en respectant les bonnes pratiques de développement et en garantissant la qualité des données saisies par les utilisateurs.


**Editeur de code** : Jetbrains PhpStorm 2023.3.2

PhpStorm offre des fonctionnalités telles que la complétion de code intelligente, la détection d'erreurs de syntaxe et d'autres problèmes de code en temps réel, la navigation aisée dans le code, le débogage avancé, la gestion de version intégrée, l'intégration avec des outils de développement populaires, la refactoring automatisé, et bien plus encore. Toutes ces fonctionnalités contribuent à améliorer la productivité des développeurs et à accélérer le processus de développement.


**Durée de BTP Management**: 2j


