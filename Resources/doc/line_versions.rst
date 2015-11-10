# Liste des offres

Cette interface présente l'ensemble des lignes (_line_) enregistrées en base. 
Le numéro de la ligne (_line.number_) et le mode de transport associé (_physical_mode.name_) sont affichés. Si la ligne est associée à au moins une offre (_line_version_), le numéro de ligne est stylisé en utilisant les couleur de texte (_line_version.fg_color_id_) et de fond (_line_version.fg_color_id_) renseignées. Sinon, le numéro est affiché avec le style par défaut. 

# Liste des offres actuelles

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

# Gestion des calendriers

## Saisie des grilles horaires (grid_calendar)

### Description

Une fois l'import FH terminé, l'opérateur saisit les nouvelles grilles horaires qui apparaitront dans TimeTable.

### Champs en base

Pour chaque _grid_calendar.id_ voulu, l'opérateur saisit les élements suivants : nom et circulation nominale de cette grille

*Intitulé input*|*Type d'input*|*Données alimentant l'input*|*Table et champs TID alimentés*|*Commentaire*
-----------|----------|--------------|------------|-----------
Nom de la grille|text box|-|_grid_calendar.name_|
monday|CheckBox|-|_grid_calendar.monday_|Circulation du service le lundi
tuesday|CheckBox|-|_grid_calendar.tuesday_|Circulation du service le mardi
wednesday|CheckBox|-|_grid_calendar.wednesday_|Circulation du service le mercredi
thursday|CheckBox|-|_grid_calendar.thursday_|Circulation du service le jeudi
friday|CheckBox|-|_grid_calendar.friday_|Circulation du service le vendredi
saturday|CheckBox|-|_grid_calendar.saturday_|Circulation du service le samedi
sunday|CheckBox|-|_grid_calendar.sunday_|Circulation du service le dimanche
action|button|-||Création ou suppression de la grille

## Calendriers des services (grid_mask_type)

### Description

Ce tableau présente les différents calendriers disponibles dans les données sources (_grid_mask_type_) ainsi que le nombre de services qui y est rattaché.

### Champs en base

|*Intitulé input*|*Type d'input*|*Données alimentant l'input*|*Table et champs TID alimentés*|*Commentaire*|
-----------|----------|--------------|------------|-----------
mask_name|Label|_grid_mask_type.calendar_type_ && _grid_mask_type.calendar_period_|-|
nb_trips|Label|count(_trip_calendar.id_|-|Nombre de services du même motif
trip_monday|Label|_trip_calendar.monday_|-|Circulation du service le lundi
trip_tuesday|Label|_trip_calendar.tuesday_|-|Circulation du service le mardi
trip_wednesday|Label|_trip_calendar.wednesday_|-|Circulation du service le mercredi
trip_thursday|Label|_trip_calendar.thursday_|-|Circulation du service le jeudi
trip_friday|Label|_trip_calendar.friday_|-|Circulation du service le vendredi
trip_saturday|Label|_trip_calendar.saturday_|-|Circulation du service le samedi
trip_sunday|Label|_trip_calendar.sunday_|-|Circulation du service le dimanche

## Affectation des calendriers aux grilles (grid_link_calendar_mask_type)

### Description

L'utilisateur affecte les différents calendriers présents dans les données dans les différentes grilles horaires par drag and drop. Au fur et à mesure que les calendriers sont affectés, ils disparaissent de la liste des calendriers restants.
Lorsqu'un grid _mask_type est affecté à un grid_calendar, un nouvel enregistrement se fait dans la table : _grid_link_calendar_mask_type_.
Les calendriers peuvent ne pas être affectés à une grille (ils n'apparaitront pas dans TimeTable).
Une grille peut ne pas avoir de calendrier (elle apparaitra vide dans TiemTable).

### Champs en base

*Intitulé input*|*Type d'input*|*Données alimentant l'input*|*Table et champs TID alimentés*|*Commentaire*
-----------|----------|--------------|------------|-----------
Grid|Label|_grid_calendar.name_|_grid_link_calendar_mask_type.grid_calendar_id_|
calendar|Label|_grid_mask_type.calendar_type_ && _grid_mask_type.calendar_period_|_grid_link_calendar_mask_type.grid_mask_type_id_|


# Gestion des exceptions

## Detection automatique

Lors de l'affectation des calendriers aux grilles, si la circulation d'un service (_trip_calendar.*day_) ne correspond pas à la grille horaires à laquelle il a été affecté (_grid_calendar.*day_), une exception est levée.
Si le pattern des différence est connu (_exception_type.*_), l'exception correspondante est associée au service (_trip.comment_id_). Sinon une exception automatique est associée.

## Edition manuelle

## Description

Une fois les exceptions et doublons détectés, les services sont affichés dans autant de tableau que de _grid_calendar_ (aller plus retour).
Les services qui ont une exception (comment_id NOT NULL) sont en haut de tableau.

Les notes 'zz' ont été détectées automatiquement mais aucun traitements n'a pu être effectué. Elles doivent être traités manuellement.

Les cases à cocher à coté de chaque note permettent d'éditer plusieurs notes à la fois. Une note peut être supprimée si la note et le texte sont supprimés.

Le bouton sauvegarder ne peut être activé que si : 

* il n'y a pas de note non vide avec un texte vide
* il n'y a pas de texte vide avec une note non vide
* il n'y a plus de note 'zz'

Charge à l'opérateur de vérifer que :

* il n'y a pas plusieurs notes identiques avec un texte différent dans un même grid_calendar
* il n'y a pas plusieurs notes différentes avec un texte identiques dans un même grid_calendar

### Champs en base

*Intitulé input*|*Type d'input*|*Données alimentant l'input*|*Table et champs TID alimentés*|*Commentaire*|
-----------|----------|--------------|------------|-----------
num_service|Label|<trip.name>|-|Numéro du service
days_service|Label|<trip_calendar.%day>|-|Jours de circulation du service
departure|Label|<stop_history.name> et <stop_time.departure_time>||Arrêt et horaire du premier départ du service
arrival|Label|<stop_history.name> et <stop_time.departure_time>||Arrêt et horaire du terminus du service
note|Text box|<comment.label>, default 'zz'||Pour éditer plusieurs note simultanément, cocher la case
exception|Text box|<comment.comment_text>, default 'Une exception a été détectée mais aucun traitements n'a pu être effectué'||

