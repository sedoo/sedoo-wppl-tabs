# sedoo-wppl-tabs

## Table des matières

- [Installation](#installation)
  - Créer un groupe de champs avec ACF Pro
  - Importer
  - Lier ACF Pro avec sedoo-wppl-tabs
- [Utilisation](#utilisation)
  - Créer un module d'onglets
  - Ajouter un module d'onglets à une page ou un article
 
## Installation

Pré-requis : 
- [Advanced Custom Fields Pro (ACF Pro)](http://www.advancedcustomfields.com/pro/)

1. Installer et activer **ACF Pro**
2. Installer et activer **sedoo-wppl-tabs** via **GitHub Updater**

Si vous disposez déjà du fichier d'export du groupe de champs, aller directement à la section [Importer](#importer)

### Créer un groupe de champs avec ACF Pro 

Dans `ACF > Ajouter` : renseigner le titre "Tableau à onglets"

Répéter les opérations suivantes 6 fois (en modifiant le chiffre) pour obtenir six onglets : 

1. Cliquer sur le bouton `+ Ajouter`
2. Renseigner le titre du champ : "Onglet n°1"
3. Renseigner son type : "Onglet"
4. Cliquer sur le bouton `+ Ajouter`
5. Renseigner le titre du champ : "Onglet n°1 - Titre"
6. Renseigner le nom du champ : "title-tab1-custom_tabs"
7. Renseigner son type : "Texte"
8. Cliquer sur le bouton `+ Ajouter`
9. Renseigner le titre du champ : "Onlget n°1 - Contenu"
10. Renseigner le nom du champ : "content-tab1-custom_tabs"
11. Renseigner son type : "Éditeur WYSIWYG"

Cliquer sur `Publier`

### Importer

Importer directement le fichier `acf-export-2017-10-24.json` décrivant le groupe de champs.

Dans `ACF > Outils`, sélectionner le fichier à importer avec le bouton `Parcourir...`

Cliquer sur `Importer`

### Lier ACF Pro avec sedoo-wppl-tabs

1. Aller dans `Tabs Settings`
2. Vérifier que le `select` contient "Tableau à onglets"

**NB :** Il se peut qu'il faille d'abord créer un module d'onglets pour que cela fonctionne (cf. **Créer un module d'onglets**, ci-dessous)

3. Remplir les inputs avec les noms des champs ACF (ceux en minuscule)

Ex : 
- Dans **Titre onglet n°1**  écrire **title-tab1-custom_tabs**
- Dans **Contenu onglet n°1** écrire **content-tab1-custom_tabs**
- etc.

## Utilisation

### Créer un module d'onglets

1. Aller dans `Modules d'onglets`
2. Cliquer sur `Ajouter`
3. Renseigner un titre *(pas obligatoire, mais plus facile pour le retrouver ensuite)*
4. Renseigner les champs titre et contenu des onglets *(les champs vides ne seront pas pris en compte)*

**NB :** le contenu peut avoir des images, une mise en forme particulières, etc. Comme pour l'édition d'un article. 

### Ajouter un module d'onglets à une page ou un article

1. Se rendre sur l'édition d'une page ou d'un article
2. Cliquer sur le bouton `Tabs Button` de l'éditeur WYSIWYG
3. Sélectionner le module d'onglets à insérer
4. Cliquer sur `OK`

Un shortcode apparaît alors et génère le code sur la page ou l'article concernée.
