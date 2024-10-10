<?php

set_include_path("./DM_Mini_Site");

/* Inclusion des classes utilisées dans ce fichier */
require_once("vue/View.php");


class Router {
    //stockage de données
    private $storage="";
    //la fonction main
    function main(){
        session_start();

    $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';

        $view = new View($this,$feedback);
        $ctrl = new Controller($view);

        /* Analyse de l'URL */
		    $articleId = key_exists('article', $_GET) ? $_GET['article'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;
        $account=key_exists('account',$_SESSION)?$_SESSION['account']:null;

		if ($action === null) {
			/* Pas d'action demandée : par défaut on affiche
	 	 	 * la page d'accueil, sauf si une couleur est demandée,
	 	 	 * auquel cas on affiche sa page. */
			$action = ($articleId === null) ? "accueil" : "voir";
        }
        try{
            switch ($action) {
                    case 'voir':
                        if($action===null)
                            $ctrl->showPostList();
                        else{
                            $ctrl->showPostInformation($articleId);
                        }break;
                    case 'detailPost':
                        $ctrl->detailPost($articleId);
                    case "publier"://la page d'inscription
                        $ctrl->makePublishPage();
                        break;

                    case "sauverpost"://la page d'inscription
                        
                        $ctrl->sauverNouveauPost($_POST);
                        break;
                    case "inscription"://la page d'inscription
                        $ctrl->nouveauCompte();
                        break;
                    case "sauverNouveauCompte":
                        $ctrl->sauverNouveauCompte($_POST);
                        break;
                    case "connexion"://se connecter
                        $ctrl->connexionPage();
                        break;
                    case "invite"://se connecter en invite
                        $ctrl->logInvite();
                        break;
                    case 'privateAccount':
                        $ctrl->privateAccount($_POST);break;
                    case 'deconnexion':
                        $ctrl->deconnexion();
                        break;
                    case 'supprimerPost':
                        $ctrl->supprimerPost($articleId);
                        break;
                    case 'update':
                        $view->makeAccountPage($account);break;
                    case 'admin':
                        $view->makeAdminPage();break;
                    case 'comment':
                        $ctrl->saveComment($articleId);break;
                    case 'modifyPassword':
                        $ctrl->modifyPassword();break;
                    case 'modifyPasswordPage':
                        $ctrl->showModifyPassword();break;
                    case 'apropos':
                        $view->apropos();break;
                    case 'accueil':
                        $ctrl->showPostList();
                            break;
                    default:
                        $content = "unknown entity";
                }
            }
            catch(Exception $e){
                //page demandée non prévue
                //$view->makeUnexpectedErrorPage($e);
            }
        $view->render();
    }


   ///Se connecter en tant qu'invite
    public function getInviteURL(){
       return ".?action=invite";
   }

   public function getAdminURL(){
      return ".?action=admin";
  }
    //enregistrer un nouveau compte
    public function saveCreatedAccount(){
        return ".?action=sauverNouveauCompte";
    }

    //lien dinscription
     public function getInscriptionURL(){
        return ".?action=inscription";
    }

    //lien dinscription
     public function saveComment($id){
        return ".?article=$id&amp;action=comment";;
    }

    //se connecter
    public function getConnexionURL(){
        return ".";
    }
    //connexion reussie
    public function getSessionUrl(){
        return ".?action=privateAccount";
    }
    ///modifier un compte
    public function getModifiedPassword(){
        return ".?action=modifyPassword";
    }
    public function getModifiedPasswordURL(){
        return ".?action=modifyPasswordPage";
    }
    //liste des Articles
    public function getListePosts(){
        return ".?action=accueil";
    }

    //lien sauvegarde de post
     public function savePost(){
        return ".?action=sauverpost";
    }
    //se deconnecter
    public function deconnexion(){
        return '.?action=deconnexion';
    }
    //suppresson d'un compte
    public function supprimerCompte($id){
        return ".?account=$id&amp;action=supprimerCompte";
    }
    //suppression d'un post
    public function supprimerPost($id){
        return".?article=$id&amp;action=supprimerPost";
    }
    //modifier un compte
    public function modifierCompte($id){
        return ".?account=$id&amp;action=modifierCompte";
    }
    //enregistrer les données modifiées du compte
    public function updateModifiedAccount($id){
        return ".?id=$id&amp;action=update";
    }
    //acceder au detail d'un post
    public function detailPost($id){
        return ".?article=$id";
    }
    public function apropos(){
        return ".?action=apropos";
    }
   public function getPostForm(){
     return ".?action=publier";
   }

   /* Fonction pour le POST-redirect-GET,
 	 * destinée à prendre des URL du routeur
 	 * (dont il faut décoder les entités HTML) */
	public function POSTredirect($url, $feedback) {
		$_SESSION['feedback'] = $feedback;
		header("Location: ".htmlspecialchars_decode($url), true, 303);
        die;
    }
}

?>
