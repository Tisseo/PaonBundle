# Liste des schémas

Les schémas de lignes sont indépendants des fiches horaires. Ils peuvent être
modifiés ou créés indépendamment des fiches horaires. Une fiche horaire est
associée à 1 seul schéma de ligne. Les utilisateurs ont des actions différentes
et se répartissent en 2 groupes : IV et SIG.

L'interface permet de visualiser l'ensemble des lignes de transport ayant une
offre en cours ou à venir, classées par mode de transport et tous les schémas
qui y sont associés.

Pour chaque ligne on affiche : le numéro, le nom commercial, la date du dernier
schéma disponible, le dernier commentaire, le groupe de ligne dont le schéma
fait partie. La ligne du tableau est surlignée en rouge si il existe sur cette
ligne de transport un commentaire postérieur au dernier schéma déposé.

Le bouton schéma a une action différente en fonction du profil de l'utilisateur.

Utilisateur IV

* Consulter les schémas disponibles pour une ligne
* Demander un nouveau schéma au SIG pour une ligne
* Déclarer qu'un schéma est utilisé dans une voussure
* Déclarer qu'un schéma est obsolète

Utilisateur SIG

* Consulter les schémas disponibles pour une ligne
* Déclarer une modification prévue ou en cours sur un schématique de ligne
* Uploader un schéma 
* supprimer un schéma

### Consulter la liste des schémas

Tableau de la liste des schémas associés à la ligne avec les champs suivants :
Nom, présence d'un fichier, date, commentaire, prévisualisation des schémas
(_schematic.name_, _schematic.file_path_ IS NOT NULL, _schematic.date_, _schematic.comment_)

### Demande de nouveau schéma par l'IV

Au clic sur le bouton "Demander un nouveau schéma", une nouvelle interface de
composition de mail s'affiche avec l'objet, la date et l'auteur pré-rempli et
deux fenêtre de saisie : destinataire et Message

###Déclarer qu'un schéma est utilisé dans une voussure

Permet de déclarer qu'un schéma est utilisé dans une voussure via le booléen _schematic.group_gis_

###Déclarer qu'un schéma est obsolète

Permet de déclarer qu'un schéma est obsolète et ne doit plus être utilisé
dans une voussure via le booléen _schematic.deprecated_.

### Déclaration de modification de schématique par le SIG

Lorsqu'un travail est en cours ou prévu par le SIG sur le schématique d'une ligne.
La création d'une nouvelle modification crée un enregistrement dans la table _schematic.*_.
Le champs _schematic.date_ est automatiquement rempli par la date du jour.

### Dépot d'un nouveau schématique par le SIG

Une fois qu'un nouveau schématique est finalisé, le clic sur le bouton
"Déposer un fichier" permet d'uploader le fichier sur le serveur.

Si le schématique est associé à une ligne présente dans un groupe de ligne au
sens SIG (_line_group_gis_content.line_id_), une alerte est levée :
__"Ce schéma est utilisé dans la voussure _line_group_gis.name_, penser à le mettre à jour."__

### Supprimer un schéma

Permet de supprimer le fichier et l'enregistrement dans la table _schematic_.

# Liste des voussures

Certains schémas de lignes sont regroupés dans des voussures (panneaux présents
à l'intérieur des bus). Une voussure peut contenir, 1, 2, 3 voire 4 schématiques
différents. La modification d'un des schématiques de la voussure doit lever une
alerte sur la présence du schématique dans la voussure, voir plus haut.
Une voussure comporte un nom _line_group_gis.name_ et un ensemble de lignes
_line_group_gis_content.line_id_. Pour sélectionner les lignes, une liste déroulante
de l'ensemble des _line.number_ est proposée. On peut ajouter autant de lignes
que nécessaire via le bouton "Plus".

Pour chaque groupe, la liste présente pour chaque ligne du groupe :

* son numéro : _line.number_
* le dernier schéma : _MAX(schematic.date)_
* le premier schéma utilisable : _MIN(schematic.date) WHERE _deprecated_ IS NOT TRUE_
* le schéma  présent sur la FH : _schematic.date_
* le dernier schéma  sur la voussure : _MAX(schematic.date) WHERE _group_gis IS TRUE_

Des règles de coloration existent : 

* fond orange si "Date du schéma voussure" > "Date du schéma FH"
* fond rouge si "Date du schéma voussure" + 2 mois < "Date du schéma FH"
