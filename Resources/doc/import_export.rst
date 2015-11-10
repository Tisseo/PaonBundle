# Import / Exports

Cette interface présente l'ensemble des actions d'import et d'export de données liées à la base TID mais aussi de voir si des taches sont en cours, et par qui elles ont étés lancées.
De plus, si une tache est en cours, alors l'application bloquera le déclenchement d'autres job et les boutons devront êtres grisés.

En pratique, les taches possibles sont des jobs jenkins.

# Actions possibles

* Export vers PREPROD : exporte les données destinées au calculateur d'itinéraire vers une base de pré-production
* Export vers PROD  : exporte les données destinées au calculateur d'itinéraire vers une base de production
* Export vers TimeTable : exporte les données destinées à la construction de fiches horaires vers Navitia puis TimeTable
* Import FH : Importe les données fournies par Hastus pour le paramétrage des offres horaires dans PAON, affecte les services dans les différentes offres crées via l'interface auparavant.
* Import PROD : Importe les données fournies par Hastus pour le le calcul d'itinéraire