<?php

set_include_path("./");


require_once("DM_Mini_Site/model/AccountCreation.php");
require_once("DM_Mini_Site/model/PostCreation.php");

class View{

    private $router;
    private $title;
    private $content;
    private $style;
    private $account;

    function __construct(Router $router, $feedback){
        $this->feedback = $feedback;
        $this->router=$router;
        $this->title=null;
        $this->content=null;
    }

    function render(){
        include "Squelette.php";
    }
    //page de connexion
    public function makeLoginFormPage(AccountCreation $builder){
      $auth="<div class='formulaire'>
      			    	<h3 class='panel-title'>Connexion</h3>
      			    	   <form accept-charset='UTF-8' action='".$this->router->getSessionUrl()."' method='post' role='form'>
      			    		    <input class='form-control' id='username' placeholder='Nom utilisateur' value='".$builder->getData(AccountCreation::USERNAME)."' name='username' type='text'>";
                        if($builder->getError(AccountCreation::USERNAME)){
                          $auth.="<span class='rouge'> ".$builder->getError(AccountCreation::USERNAME)."</span>";
                        }
      $auth.=          "<input class='form-control' id='password' placeholder='Mot de Passe' name='password' type='password'>";
                        if($builder->getError(AccountCreation::PASSWORD)){
                           $auth.="<span class='rouge'> ".$builder->getError(AccountCreation::PASSWORD)."</span>";
                          }
      $auth.=          "<input class='btn btn-lg btn-success btn-block' name='Connecter' id='Connecter' type='submit' value='Se connecter'>
                       <br/>";
                        //Si la variable n'est pas vide on echo la variable d'erreur
                        if($builder->getError('loginOuPASSWORDIncorrect')!==null){
                            $auth.="<span class='rouge'> ".$builder->getError('loginOuPASSWORDIncorrect')."</span>";
                        }
                        $builder->addError('loginOuPASSWORDIncorrect',null);
      $auth.=          "<a href='".$this->router->getInscriptionURL()."'> Vous n'avez pas de compte? Cliquez ici!</a>";
      $auth.=       "</form>
      			  </div>";
      //Echo le form le connexion
      $this->title = "Login";
      $this->content.=$auth;
    }

    /*la page Admin d'un compte*/
    public function makeAdminPage(){
      $body= "<form action='' method='POST' >
               <div class='formulaire' style='margin-top:5%; margin-bottom:5%;'>
                <div>
                  <div>
                    <div>
                      <div>
                       <h3>Supprimer un utilisateur</h3>
                     </div>
                     <div style='overflow:scroll; height:200px;'>
                     <fieldset>";
                     $userbd=new AccountStorage();
                     $postbd=new PostStorage();
                     $logsbd=new LogsStorage();
                     $Array=$userbd->readAll2();
                     //supprimer un utilisateur================
                     if (isset($_POST['Supprimer'])) {
                       $userr=$_POST['Supprimer'];
                       $userbd->delete($userr);
                       $postbd->deleteWithName($userr);
                     }
                    if(!empty($Array)){
                    //Lis ce que contient le tableau en partant de la fin
                    for($i = count($Array)-1 ; $i >= 0 ; $i--)
                    {
                        $us=$Array[$i]['username'];
                        $body.= "<button class='supprimer' name='Supprimer' type='submit' value='$us'>Supprimer $us</button>";
                    	  $body.= "<br>";
                    }
                  }
      $body.="     </fieldset>
                </div>
             </div>
           </div>
         </div>";

      $body.= "<div>
                  <div>
                    <div>
                      <div>";

      if($logsbd)
      {
          $body.= "<div>
                    <table>
                      <thead>
                         <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                        <th>Adresse IP</th>
                    </tr>
                  </thead>
                  <tbody>";
       ////==========================lecture des logs
            if(isset($_POST['Supprimer'])){
            $userr=$_POST['Supprimer'];
            }
            $ArrayL=$logsbd->readAll();

          if (!empty($ArrayL)) {
              $cpt = 1;
              for ($i = count($ArrayL) - 1; $i >= count($ArrayL) - 10 ; $i--) {
                  if($i >= 0)
                  {
                      $usL=$ArrayL[$i]['username'];
                      $dateL = $ArrayL[$i]['log_date'];
                      $ipadress = $ArrayL[$i]['ip'];
                $body.= "<tr>
                         <td>$cpt</td>
                         <td>$usL</td>
                         <td>$dateL</td>
                         <td>$ipadress</td>
                     </tr>";
                      $cpt++;
                  }
              }
          }

      $body.= "</tbody>
          </table>
        </div>";
      }
      $body.= "        </div>
                  </div>
              </div>
          </div>
      </div>";

        $this->title = "Admin Page";
        $this->content=$body;
    }

      //page de publication de post (image)//==========================================================
      public function makePublishForm(){
        $body= "<div class='formulaire'>
                   <h3 class='panel-title'>Publier une image</h3>
                     <form accept-charset='UTF-8' action='".$this->router->savePost()."' enctype='multipart/form-data' method='POST'  role='form'>
                        <input class='form-control' maxlength='20' placeholder='Titre de l&#39;image' name='titre' type='text' required>
                        <input class='form-control' maxlength='200' placeholder='Legende de l&#39;image' name='legend' type='text' required>
                        <input type='file' name='ImageToUpload' id='ImageToUpload'>
                        <input class='btn btn-lg btn-success btn-block' name='GestionImageSubmit' type='submit' value='Valider'>
                     </form>";
        $body.= "</div>";
        $this->title ="Publier";
        $this->content=$body;
      }

    /*modification d'un compte*///==========================================================
    public function makeModifyPassword(){
     $error="";
      //verifications
      if(isset($_POST["Connecter"])){
        if(password_verify($_POST['password'],$_SESSION['account']->getPassword())){
          if(password_verify($_POST['newpassword'],$_SESSION['account']->getPassword())===false){
           if($_POST['newpassword']!==$_POST['confirmpassword']){
              $error="Nouveau Mot de Passe != Confimation";
           }
          }
          else{
              $error="Nouveau Mot de Passe == Ancien Mot de Passe";
          }
         }
          else{
              $error="Mot de Passe actuel incorrecte";
          }
        }
      //====================
      $auth="<div class='formulaire'>
               <h3 class='panel-title'>Changer Mot de Passe</h3>
               <form accept-charset='UTF-8' action='".$this->router->getModifiedPassword()."' method='post' role='form'>
                  <input class='form-control' id='password' placeholder='Mot de Passe Actuel' name='password' type='password' required>";
      $auth.="	  <input class='form-control' id='password' placeholder='Nouveau Mot de Passe' name='newpassword' type='password' required>";
      $auth.="    <input class='form-control' id='password' placeholder='Confirer le Mot de Passe' name='confirmpassword' type='password' required>";
                  if(isset($_POST["Connecter"])){
                    $auth.="<span class='rouge'> ".$error."</span>";
                  }
      $auth.= "   <input class='btn btn-lg btn-success btn-block' name='Connecter' id='Connecter' type='submit' value='Modifier Mot de Passe'>
                  <br/>";
      $auth.= " </form>
      			  </div>";
      //Echo le formulaire de connexion
      $this->title = "Changer Mot de Passe";
      $this->content=$auth;
    }


    /*afficher un formulaire de création d'un compte*///==========================================================
    public function makeAccountCreationPage($builder){
      $formulaire="<div class='formulaire' style='margin-top:5%; margin-bottom: 5%'>
                     <h3 class='panel-title'>Creer un compte</h3>
                       <form accept-charset='UTF-8' action='".$this->router->saveCreatedAccount()."' method='post' role='form'>
                         <input class='form-control' id='username' placeholder='Nom utilisateur' name='username' type='text'>
                         <input class='form-control' id='password' placeholder='Mot de Passe' name='password' type='password'>
                         <input class='btn btn-lg btn-success btn-block' name='CreerUnCompte' id='Connecter' type='submit' value='Creer un compte'>";
                         //Si la variable n'est pas vide on echo la variable d'erreur
                         if(key_exists('loginExistence',$builder->getErrtable()) && $builder->getError('loginExistence')!==null){
      $formulaire.=      "<span class='rouge'> <p>".$builder->getError('loginExistence')."</p></span>";
                         }

      $formulaire .=   "</form>
      			         </div>";
      $this->title="Creer un compte";
      $this->content .= $formulaire;
   }

   /*afficher la liste des posts sur la page d'accueil*///==========================================================
    public function makeListPage($liste){
      //verifie si l'utilisateur est connecte si non le redirige vers la page de connexion
      $main="<div class='galleryrow'>";
      for($i = count($liste) - 1; $i >= 0; $i--){
      $href="#";
      if(isset($_SESSION["account"])){
            $href="".$this->router->detailPost($liste[$i]->getId());
       }
       else{
            $href="".$this->router->deconnexion();
       }
        $nbcommentbd=new CommentStorage();
        $nbcomments=$nbcommentbd->readAll($liste[$i]->getId());
        $totalcomments=count($nbcomments);
        $main .= "<div class='gallery'>
                  <a href=$href name='ImageClicker' type='submit'>
                     <img src='DM_Mini_Site/image/".$liste[$i]->getimg_name()."'style='width:175px; height:120px;' />
                   <div class='desc'>
                     <small class='text-muted'><b>".$liste[$i]->getTitre()."</b></small><br>
                     <small class='text-muted'>Publié par @".$liste[$i]->getUser()."</small><br>
                     <small class='text-muted'>".$liste[$i]->getimg_date()."</small><br>
                     <small class='text-muted'>$totalcomments commentaires</small>
                  </div>
                 </a>
              </div>";
        }
        $main.="</div>";
        $this->content.=$main;
        $title = "Home";

        $this->title = $title;

    }

     /*page affichant les détails d'un post*/
     public function makeDetailPage(Post $post){
      $comments=array();
      if(isset($_SESSION["account"])){
        $main= "<div class='formulaire'>";
        $main .= "";
        $main .= "";
        $main .= "<h1>";
        $main .= " ".$post->getTitre();
        $main .= "</h1>";
        $main .= "<img style='width:710px; height:400px;' src='DM_Mini_Site/image/".$post->getimg_name()."'>";
        $main .= "";
        $main .= "";
        $main .= "";
        $main .= "<h3>Legende</h3>";
        $main .= "<p>";
        $main .= " ".$post->getLegend();
        $main .= "</p>";
        $main .= "<form  action='".$this->router->supprimerPost($post->getId())."' method='POST' enctype='multipart/form-data'>";
        if ($_SESSION['account']->getUsername() === $post->getUser() || $_SESSION['account']->getUsername() === "admin") {
            $main .= "<input class='btn btn-lg btn-success btn-block' name='SupprimerImage' type='submit' value='Supprimer'>
                       <input type='hidden' name='ImageSupp' value=' Image commentaire '>";
        }
        $main .= "</form>";
        $main .= "";

        $main .= "";
        $main .= "";

        $main .= "

    <form  action='".$this->router->saveComment($post->getId())."' method='POST' enctype='multipart/form-data'>
            <h4>Commentaires de la photo</h4>
                <input type='text' maxlength='150' name='LeCommentaire' class='form-control input-sm chat-input' placeholder='Ecrivez votre commentaire ici...' />
                <span class='input-group-btn'>
                    <button type='submit' name='EnvoyerCommentaire' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-comment'></span> Commenter</button>
                </span>";


                $commentbd=new CommentStorage();
                $comments=$commentbd->readAll($post->getId());

                for($i = count($comments) - 1; $i >= 0; $i--){
                $user=$comments[$i]['user'];
                $content=$comments[$i]['content'];
                $date=$comments[$i]['cdate'];
                $main.= "
                    <hr data-brackets-id='12673'>
                    <ul data-brackets-id='12674' id='sortable' class='list-unstyled ui-sortable'>
                        <strong class='pull-left primary-font'>$user</strong>
                        <small class='pull-right text-muted'>
                        <span class='glyphicon glyphicon-time'></span>$date</small>
                        </br>
                        <li class='ui-state-default'>$content</li>
                        </br>
                    </ul>";

              }

        $main .= "</form>";
        $main .= "</div>";
        $this->content.=$main;
        $this->title =$post->getTitre();
         }
       }


/* la barre de navigation */
public function navig(){//la barre de navigation: bien organiser la navigation
  $text="Connexion";
  $nav= "<div class='navbardiv'>";

  $nav.=    "<ul class=\"navbar\">\n
               <li><span>Galerie d'Image En Ligne</span></li>
               <li><a  href='".$this->router->getListePosts()."' >Accueil</a></li>";
              if(isset($_SESSION["account"])){
               if(key_exists('account',$_SESSION) && $_SESSION['account']->getUsername()!==null){
               $text="Deconnection";
               $user=$_SESSION["account"];
               $nav.= "<li><a  href='".$this->router->getPostForm()."' >Publier</a></li>";
               $nav.="<li><a  href='".$this->router->getModifiedPasswordUrl()."' >Profil</a></li>";
                  //Si la valeur de la variable session account username vaut un username admin alors on ajoute l'onglet admin
                  if($user->getUsername() === 'sow' || $user->getUsername() === 'arafat'  || $user->getUsername()==="vanier" || $user->getUsername()==="lecarpentier")
                  {
                      $nav.= "<li><a  href='".$this->router->getAdminURL()."' >Admin</a></li>";
                  }

               }
             }
   $nav.= "<li><a  href='".$this->router->apropos()."' >A propos</a></li>";
   //déconnection envoit un get a la page login pour qu'elle supprime les coockies et sessions
   $nav.= "<li><a  href='".$this->router->deconnexion()."' name='Logout'>$text</a></li>

             </ul>
          </div>";
    return $nav;

}
    /*le pied de page qui apparaît dans toutes les pages*/
    public function footer(){
      $message="# Vous n'etes pas connecté #";
      $footer= "<div class='footer'>";
      //Si la variable session n'est pas vide alors on montre qui est connecter
       if(isset($_SESSION['account']))
       {
         $user=$_SESSION["account"];
         if($user->getUsername()==="sow" || $user->getUsername()==="arafat" || $user->getUsername()==="vanier" || $user->getUsername()==="lecarpentier"){
           $message="Connecté en tant que @".$user->getUsername()." - vous disposez des droits Admin";
         }

         else{
           $message="Connecté en tant que ".$user->getUsername();
         }

         }
       $footer.=   "<p><h5 style='color:white;'>$message</h5></p>";
       $footer.=   "<p><h8 style='color:white;'>Application fait par 21914879 et 22014919</h8></p><br/>";
       $footer.= "</div>";

      return $footer;
    }



    public function apropos(){
        $this->title='à propos ';
        $content = "
        Etudiants: <a href='https://play.google.com/store/apps/dev?id=7259650547953379305' >21914879</a> et 22014919
            <h1> A propos du site</h1>
            <p>Il s'agit d'un site de galerie d'image en ligne. Vous pouvez regarder,commenter ou publier une image.</p>


A fin de pratiquer les notions vue en cours et remplir les consignes demandées en DM nous avons choisi d’implémenter un mini-site de galerie d'image en ligne.

Notre site comporte 4 objets importants:
Account (avec les liens connexion/deconnexion)
Post
Comment
Admin
LogsStorage
<ul>
<li>1-la classe Account : concerne l’utilisateur du site a fin qu’il puisse s’authentifier et publier,commenter ou voir les details des posts(Images).</li>
<li>2-la classe Post : la description d’un post(Image) publié sur le site.</li>
<li>3-la classe Comment : correspond a l’ensemble des commentaires ecrit sur chaque post (chaque possede ses propres commentaires).</li>
<li>4-la classe LogsStorage : correspond l'historique de connexion des utilisateur sur le site.</li>
</ul>
 A savoir sur notre site :
<p>
 -Un utilisateur peut s'inscrire en fournissant son nom d'utilisateur et son mot de passe
-Un utilisateur connecté et non connecté peut voir les diffrents posts du site
-Un utilisateur non connecté ne peut pas voir les details relatifs aux posts ni les commenter
-Un utilisateur non connecté peut s'inscrire
- Un utilisateur connecté peux modifier son propre mot de passe,publier,supprimer(son post),commenter un post
- Un simple utilisateur Admin peut avoir acces a la page admin et pouvoir supprimer des utilisateur ou voir l'historique des connexions(logs)
</p>

<h3>Les complements realisé :</h3>
<p>
# Associer des images aux objets -> Un objet peut être illustré par zéro, une ou plusieurs images (modifiables) uploadées par le créateur de l'objet.
# Gestion par un admin des comptes utilisateurs.
# Commentaires sur un objet.
</p>

Repartitions des taches:
<p>
  Le travail a ete realise sur le meme ordinateur, nous sommes entre aide pour resoudre les problemes au fur et a mesure.
</p>

les contraintes techniques :
<p>
-Utilisation de l’architecture MVCR vue en cours
-Utilisation de MYSQL
-Valisation du html5
</p>";
        $this->content=$content;
    }

	/* Une fonction pour échapper les caractères spéciaux de HTML,
	* car celle de PHP nécessite trop d'options. */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			/* on échappe guillemets _et_ apostrophes : */
			ENT_QUOTES
			/* les séquences UTF-8 invalides sont
			* remplacées par le caractère �
			* au lieu de renvoyer la chaîne vide…) */
			| ENT_SUBSTITUTE
			/* on utilise les entités HTML5 (en particulier &apos;) */
			| ENT_HTML5,
			'UTF-8');
	}
    //facilite le debug en affichant le contenu d'une variable
    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.var_export($variable, true).'</pre>';
    }
}
