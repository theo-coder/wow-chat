**<h1 align="center">WOW chat</h1>**

<div align="center">

![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)
![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?style=for-the-badge&logo=bootstrap&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

</div>

---

## 📝 Table des matières

- [Présentation](#presentation)
- [Installation](#installation)
- [Parcours Utilisateur](#userjourney)
- [Contributeurs](#contributors)

---

## 📃 Présentation <a name="presentation"></a>

L'objectif de ce projet était d'utiliser le framework PHP `symfony` pour créer un réseau social, sous la forme d'un forum, et permettant l'échange d'informations, de messages et de fichiers.

Le projet présente les fonctionnalités suivantes:

- Authentification
- Rédaction de contenu
- Autorisation et sécurité

---

## ⚙️ Installation <a name="installation"></a>

Pour la phase de développement nous avons utilisé `docker` ainsi que `postgresql` pour la base de données.

Ansi avec `docker` et `docker-compose` d'installés, il suffit de lancer la commande suivante pour lancer la base de données:

`docker-compose up -d` _(le `-d` permet de lancer en arrière plan, pas obligatoire)_

Si vous ne souhaitez pas utiliser `docker`, il est également possible de changer les informations dans le `.env` et d'utiliser un `postgresql` local ([download postgresql windows](https://www.postgresql.org/download/windows/))

Ensuite il faut installer les dépendances liées au projet:

`composer update`

Une fois le tout installé, il est possible de lancer la création de la base de données:

`symfony console d:d:c`

⚠️ Il faut bien sur vérifier que le driver pour pdo_pgsql est actif dans le `php.ini`

Une fois ceci fait il suffit de lancer les migrations:

`symfony console d:m:m`

Et lancer les fixtures pour remplir la base de données:

`symfony console d:f:l`

Et pour terminer lancer l'application:

`symfony serve`

Tout devrait fonctionné sans problèmes.

---

## 🗺 Parcours Utilisateur <a name="userjourney"></a>

Lorsque l'on arrive sur le site, il est possible de s'inscrire, se connecter ou simplement explorer les catégories disponibles pour tous.

Dans la navbar il est possible de se créer un compte (inscription)

Il faut alors rentrer ses informations, et si tout est juste, on est ensuite connecté en tant qu'utilisateur

### **User :**

Le menu en haut à droite varie un peu, il affiche le nom d'utilisateur et propose la déconnexion ou le changement de mot de passe

Maintenant connecté avec le strict minimum de permissions, il est possible de rédiger un sujet grâce au bouton en bas de la page d'un board.

Dans le formulaire le board adequat est automatiquement sélectionné et une fois créé il est possible pour l'auteur d'éditer ou supprimer son sujet.

Il est également possible de réagir grâce à des messages et images aux différents sujets.

### **Insider / Collaborateur / Externe:**

Si le compte créé a pour adresse mail un domaine correspondant à `@insider.fr`, `@collaborator.fr` ou `@external.fr` les permissions sont différentes, il est ainsi possible de visualiser des contenus destinés à ces 3 rôles.

_(Fixtures: `username: theo@insider.fr`, `password: azerty`)_

_(`username: theo@collaborator.fr`, `password: azerty`)_

_(`username: theo@external.fr`, `password: azerty`)_

### **Editor:**

Un rôle éditeur est disponible, gérable par les administrateurs, il permet aux membres le possédant de créer et modifier boards et catégories grâce au bouton disponible dans les pages respectives.

_(Fixtures: `username: theo@editor.fr`, `password: azerty`)_

### **Admin:**

Le rôle administrateur permet l'administration de tout le site, tout comme l'éditeur des boutons sont disponible dans chaque page pour l'ajout de categorie / board.

Un menu administrateur est également disponible dans la navbar donnant accès à :

- modification / blocage des utilisateurs
- modification / supression des sujet (contenu)
- ajout / modification / supression des categories
- ajout / modification / supression des boards

_(Fixtures: `username: theo@admin.fr`, `password: azerty`)_

---

## ✨ Contributeurs <a name="contributors"></a>

Projet réalisé par les soins des dévelopeurs suivants:

<table>
    <tr>
        <td align="center">
            <a href="https://theo.posty.fr">
                <img src="https://avatars.githubusercontent.com/theo-coder?v=3?s=100" width="100px;" alt=""/>
                <br/>
                <sub>
                    <b>Théo Posty</b>
                </sub>
            </a>
        </td>
        <td align="center">
            <a href="https://github.com/devHugoB">
                <img src="https://avatars.githubusercontent.com/devHugoB?v=3?s=100" width="100px;" alt=""/>
                <br/>
                <sub>
                    <b>Hugo Bollaert</b>
                </sub>
            </a>
        </td>
        <td align="center">
            <a href="https://github.com/Yannis-prog">
                <img src="https://avatars.githubusercontent.com/Yannis-prog?v=3?s=100" width="100px;" alt=""/>
                <br/>
                <sub>
                    <b>Yannis Bonne</b>
                </sub>
            </a>
        </td>
    </tr>
</table>
