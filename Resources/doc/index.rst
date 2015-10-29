# Qu'est-ce que c'est ?

## Techniquement
L'application PAON est une interface web rattachée à un portail NMM.
Le portail NMM est une application qui centralise la gestion des utilisateurs, des droits d'accès et des périmètres d'accès à l'API Navitia, ce portail est développé par [CanalTP](http://www.canaltp.fr/).
PAON est composé de deux bundle Symfony qui sont intégrés à l'application NMM via une installation par [composer](https://getcomposer.org/).
Les interfaces sont reliées à une base EnDIV.

## Fonctionnellement
L'application est destinée essentiellement au service Information Voyageurs pour la préparation et l'administration de l'offre nominale (c'est-à-dire hors temps réel). 
Elle propose une suite de fonctionnalités décrites dans la liste des spécifications qui modifieront des données de la base ENDIV et permettront de générer un jeu de données NTFS destiné à alimenter Kraken puis TimeTABLE pour la création et la mise en forme de fiches horaires.

# Spécifications

- [a Gestion des lignes](lines.rst)
- [a Gestion des offres](line_verions.rst)
- [a Imports/Exports](import_export.rst)
- [a Gestion des schémas](schemas.rst)