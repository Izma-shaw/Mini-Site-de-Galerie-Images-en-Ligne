# Mini Site de Galerie d'Images en Ligne

## Description

Ce projet est un mini-site de galerie d'images en ligne développé en PHP. Il permet aux utilisateurs de publier, commenter, et gérer des images. Le site est conçu en suivant une architecture MVC (Modèle, Vue, Contrôleur) pour garantir une séparation claire entre la logique, la présentation et le contrôle. Ce mini-site inclut des fonctionnalités telles que l'inscription des utilisateurs, la publication de posts avec des images, et la gestion des utilisateurs par un administrateur.

## Fonctionnalités

- **Gestion des Utilisateurs** : Les utilisateurs peuvent s'inscrire, se connecter et se déconnecter. Un administrateur peut supprimer des comptes et voir l'historique des connexions.
- **Galerie d'Images** : Les utilisateurs peuvent publier des images, commenter les publications, et visualiser les images des autres utilisateurs.
- **Commentaires** : Chaque image peut être commentée par les utilisateurs connectés.
- **Gestion des Utilisateurs par un Administrateur** : Un utilisateur avec des droits d'administrateur peut supprimer des utilisateurs et visualiser les logs des connexions.
- **Sécurité** : Validation des entrées utilisateur et vérification des mots de passe.

## Prérequis

- PHP 7.0 ou version supérieure
- Serveur Web (Apache, Nginx, etc.)
- MySQL pour la gestion de la base de données

## Installation

1. Clonez ce dépôt sur votre machine locale :
   ```sh
   git clone <URL-du-dépôt>
   ```
2. Configurez votre serveur Web pour pointer vers le dossier racine du projet.
3. Créez la base de données MySQL et importez le fichier `database.sql` fourni pour créer les tables nécessaires.
4. Mettez à jour les informations de connexion à la base de données dans le fichier de configuration.
5. Assurez-vous que les permissions des dossiers sont correctement définies pour permettre le téléchargement des images.

## Utilisation

- **Page de Connexion** : Les utilisateurs peuvent se connecter en utilisant leur nom d'utilisateur et mot de passe.
- **Inscription** : Les nouveaux utilisateurs peuvent s'inscrire pour créer un compte.
- **Publication d'Images** : Une fois connectés, les utilisateurs peuvent publier des images en ajoutant un titre et une légende.
- **Commentaires** : Les utilisateurs connectés peuvent commenter les images publiées.
- **Administration** : Un administrateur peut supprimer des utilisateurs et visualiser les logs des connexions.

## Structure du Projet

- **Model** : Gère la logique de données, y compris la création de comptes et la publication de posts.
- **View** : Contient les différentes pages du site, comme la page de connexion, la page d'administration, etc.
- **Controller** : Gère les interactions utilisateur et met à jour le modèle et la vue en conséquence.

## Exemple de Code

Voici un extrait du code PHP utilisé pour gérer la connexion des utilisateurs :

```php
public function makeLoginFormPage(AccountCreation $builder){
  $auth = "<div class='formulaire'>
              <h3 class='panel-title'>Connexion</h3>
              <form accept-charset='UTF-8' action='" . $this->router->getSessionUrl() . "' method='post' role='form'>
                <input class='form-control' id='username' placeholder='Nom utilisateur' value='" . $builder->getData(AccountCreation::USERNAME) . "' name='username' type='text'>";
                
  if ($builder->getError(AccountCreation::USERNAME)) {
    $auth .= "<span class='rouge'> " . $builder->getError(AccountCreation::USERNAME) . "</span>";
  }
  
  $auth .= "<input class='form-control' id='password' placeholder='Mot de Passe' name='password' type='password'>";
                
  if ($builder->getError(AccountCreation::PASSWORD)) {
    $auth .= "<span class='rouge'> " . $builder->getError(AccountCreation::PASSWORD) . "</span>";
  }
  
  $auth .= "<input class='btn btn-lg btn-success btn-block' name='Connecter' id='Connecter' type='submit' value='Se connecter'>
            <br/>
            <a href='" . $this->router->getInscriptionURL() . "'> Vous n'avez pas de compte? Cliquez ici!</a>
            </form>
          </div>";
  
  $this->title = "Login";
  $this->content .= $auth;
}
```

## Collaborateurs


- Ismael Sow
- Arafat Feical Idriss

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

