# Liste des lignes

Cette interface présente l'ensemble des lignes (_line_) enregistrées en base. 
Le numéro de la ligne (_line.number_) et le mode de transport associé (_physical_mode.name_) sont affichés. Si la ligne est associée à au moins une offre (_line_version_), le numéro de ligne est stylisé en utilisant les couleur de texte (_line_version.fg_color_id_) et de fond (_line_version.fg_color_id_) renseignées. Sinon, le numéro est affiché avec le style par défaut. 


# Création d'une ligne

## Description

Fenêtre modale permettant de créer une nouvelle ligne et lui attribuer les propriétés suivantes : 
- numéro
- mode de transport
- priorité
- source de données et référence

## Champs en base

*Intitulé input*|*Type d'input*|*Données alimentant l'input*|*Table et champs DAHUT alimentée*
-----------|----------|--------------|------------
Numéro de la ligne | Text box | - | _line.number_
Mode de transport|Select|_physical_mode.name_ | _line.physical_mode_id_
Code|Text box||_line_datasource.code_
Source de données|Select|_datasource.name_|_line_datasource.datasource_id_
