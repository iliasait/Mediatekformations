# MediatekFormation
## Présentation
Le code source original de cette application est disponible dans le dépôt d'origine. Ce dépôt contient également une présentation détaillée de l'application d'origine dans son propre README.<br>
Voici le lien pour y accéder : https://github.com/CNED-SLAM/mediatekformation
## Les pages modifiée 
Voici les pages qui ont été modifiée
### Page 2 : les formations
Une nouvelle colonne a été ajoutée à la page des playlists afin d'afficher le nombre de formations associées à chaque playlist. <br>
Cette colonne intègre également une fonctionnalité de tri, permettant aux utilisateurs d'organiser les playlists en ordre croissant ou décroissant en fonction du nombre de formations.<br>
De plus, cette information est désormais visible sur la page dédiée à chaque playlist, offrant ainsi une meilleure visibilité sur le contenu disponible et facilitant la navigation des utilisateurs.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/playlist.png)
## Les pages ajoutés (back-office) 
Voici les pages qui ont été nouvellement crées
### Page 1 : Connexion à l'espace administrateur
Cette page permet de se connecter à l'espace administrateur du site.<br>
Celle-ci contient un formulaire très simple en son centre.<br>
Deux champs sont présent : l'identifiant et le mot de passe, ainsi qu'un bouton "Se connecter".<br>
LEn cas de saisie incorrecte, un message d'erreur s’affiche sous le champ "Mot de passe".<br>
Si les identifiants sont valides, l’utilisateur est redirigé vers la page de gestion des formations, où il peut accéder aux fonctionnalités de l’espace administrateur.<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/connexion_form.png)
### Page 2 : Gestion des formations
La partie haute est quasi identique à la page d'accueil (bannière et menu).<br>
La différence réside dans le fait qu'à droite est affiché l'espace administrateur comportant : <br>
• Un onglet de Gestion des formations <br>
• Un onglet de Gestion des playlists <br>
• Un onglet de Gestion des catégories <br>
• Un bouton de déconnexion (qui renvoie à la page d'accueil et cache l'espace administrateur) <br>
Une interface de gestion des formations a été mise en place, permettant d’afficher la liste des formations avec des boutons pour les modifier ou les supprimer après confirmation.<br>
Les tris et filtres disponibles dans le front office ont été intégrés au back office pour assurer une navigation fluide.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/gestion_des_formations.png)
### Page 3 : Ajout des formations
Le bouton d’ajout permet d’accéder à un formulaire dédié, où les saisies sont contrôlées : seule la description et la sélection de catégories restent facultatives.<br>
La playlist et les catégories sont sélectionnables via une liste déroulante, et la date, qui doit être antérieure ou égale à la date du jour, est choisie via un sélecteur.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/ajout_formation.png)
### Page 4 : Modification des formations
La modification d’une formation s’effectue à partir du même formulaire que celui pour l'ajout, qui se préremplit automatiquement avec les informations existantes.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/modification_formation.png)
### Alerte 1 : Suppression des formations
Lorsque l'on clique sur le bouton de suppression une alerte apparaît pour demander la confirmation de suppression.<br>
Si l'utilisateur clique sur OK, alors la formation est supprimée, sinon l'action et annulée et aucune modification dans la base de données n'est effectué.<br>
Lorsqu’une formation est supprimée, elle est automatiquement retirée de la playlist à laquelle elle était associée.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/suppression_formation.png)
### Page 6 : Gestion des playlists
La partie haute est quasi identique à la page d'accueil (bannière et menu).<br>
La différence réside dans le fait qu'à droite est affiché l'espace administrateur comportant : <br>
• Un onglet de Gestion des formations <br>
• Un onglet de Gestion des playlists <br>
• Un onglet de Gestion des catégories <br>
• Un bouton de déconnexion (qui renvoie à la page d'accueil et cache l'espace administrateur) <br>
La gestion des playlists a été implémentée avec succès. Une page permet désormais de lister toutes les playlists, avec un bouton pour les supprimer (après confirmation) et un bouton pour les modifier.<br>
La suppression d’une playlist n’est autorisée que si aucune formation n’est rattachée à celle-ci, ce qui garantit l'intégrité des données.<br>
Les mêmes filtres et tris que ceux présents dans le front office ont été intégrés au back office pour faciliter la gestion des playlists.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/gestion_playlist.png)
### Page 7 : Ajout des playlists
Un bouton d’ajout permet d'accéder à un formulaire où il suffit de saisir le nom et la description de la playlist.<br>
La liste des formations associées à la playlist est affichée, mais il n'est pas possible d'ajouter ou de supprimer des formations à partir de ce formulaire.<br>
Le rattachement des formations à une playlist se fait exclusivement dans le formulaire de gestion des formations.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/ajout_playlist.png)
### Page 8 : Modification des playlists
Lors de la modification d'une playlist, le formulaire se préremplit automatiquement avec les informations existantes.<br>
Si l'on clique sur le bouton de retour à la liste,la modification est annulé et rien n'est envoyé dans la base de données<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/modifier_playlist.png)
### Alerte 2 : Suppression des playlists
Une playlist ne peut être supprimée que si elle ne contient aucune formation.<br>
Dans le cas où celle-ci ne contient aucune formation, une alerte apparaît pour demander la confirmation de suppression<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/suppression_playlist.png)
### Page 10 : Gestion des Catégories
La gestion des catégories a été mise en place avec succès. Une page permet désormais de lister toutes les catégories, avec un bouton permettant de les supprimer. <br>
La suppression d'une catégorie est uniquement autorisée si elle n'est pas rattachée à des formations, ce qui assure la cohérence des données.<br>
De plus, un mini-formulaire a été intégré sur la même page, permettant d’ajouter directement une nouvelle catégorie. <br>
Avant l'ajout, le nom de la catégorie est vérifié pour s'assurer qu'il n'existe pas déjà dans la base de données, évitant ainsi les doublons.<br>
![img](https://monportefolioanis.go.yj.fr/photo_readme_mediatekformation/gestion_categories.png)
