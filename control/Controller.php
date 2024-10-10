<?php

class Controller{
    private $view;
    private $postStore;
    private $commentStore;
    private $accountStore;
    private $PostCreation;
    private $logsStore;
    private $accountCreation;
    private $currentPostBuilder;
    private $currentAccountBuilder;

    function __construct(View $view){
        $this->view=$view;
        $this->currentAccountBuilder=  key_exists('currentAccountBuilder', $_SESSION) ? $_SESSION['currentAccountBuilder'] : null;;
        $this->currentPostBuilder=  key_exists('currentPostBuilder', $_SESSION) ? $_SESSION['currentPostBuilder'] : null;;
		    $this->modifiedAccountBuilders = key_exists('modifiedAccountBuilders', $_SESSION) ? $_SESSION['modifiedAccountBuilders'] : array();
        $this->postStore = new PostStorage();
        $this->logsStore = new LogsStorage();
        $this->commentStore = new CommentStorage();
        $this->accountStore = new AccountStorage();

    }


    //detail d'un post
    public function detailPost($postId){
        return $this->view->makeDetailPage($this->postStore->read($postId));
    }



    //supprimer un post
    public function supprimerPost($id){
        /*verifie le post en bdd*/
        $article=$this->postStore->delete($id);
        $this->showPostList();
    }

    //supprimer un post
    public function saveComment($id){
        /*verifie le post en bdd*/
        $comment=$this->commentStore->create($id);
        $this->detailPost($id);
    }

    //afficher les informations
    public function showPostInformation($id){
        if($this->postStore->read($id)!==-1){
            $this->view->makeDetailPage($this->postStore->read($id));
        }

    }

    //page de modification de mot de passe
    public function modifyPassword(){
        /* Affichage du formulaire de création de compte
        * avec les données par défaut. */
        if(isset($_POST["Connecter"])){
          if(password_verify($_POST['password'],$_SESSION['account']->getPassword())){
            if(password_verify($_POST['newpassword'],$_SESSION['account']->getPassword())===false){
              if($_POST['newpassword']===$_POST['confirmpassword']){
               $this->accountStore->createPass();
               $this->accountStore->deleteOld($_SESSION['account']->getPassword());
               $this->deconnexion();
            }
            else{
               $this->view->makeModifyPassword();
            }
           }
            else{
               $this->view->makeModifyPassword();
            }

          }
          else{
              $this->view->makeModifyPassword();
          }

          }
    }

   //page de modification de compte
    public function showModifyPassword(){
           $this->view->makeModifyPassword();
        }

 //pour creer un nouveau compte
    public function nouveauCompte(){
        /* Affichage du formulaire de création de compte
        * avec les données par défaut. */
        if($this->currentAccountBuilder===null){
            $this->currentAccountBuilder=new AccountCreation();
        }
        $this->view->makeAccountCreationPage($this->currentAccountBuilder);

    }
    //enregistrer un nouveau compte
    public function sauverNouveauCompte($data){
        $this->currentAccountBuilder = new AccountCreation($data);
        if ($this->currentAccountBuilder->isValid()){
  			/* On crée le nouveau compte */
            $account = $this->currentAccountBuilder->createAccount($this->accountStore);
            if($account===false)
                $this->view->makeAccountCreationPage($this->currentAccountBuilder);
            else{/* On l'ajoute en BD */
                $this->accountStore->create($account);
                /* On détruit le builder courant */
                $this->currentAccountBuilder = null;
                /* On redirige vers la page parametre de l'utilisateur*/
                $this->connexionPage();

            }
        }else
            $this->view->makeAccountCreationPage($this->currentAccountBuilder);
    }

    //authentification
    public function privateAccount($authData){

        $this->currentAccountBuilder = new AccountCreation($authData);
        $isValid = $this->currentAccountBuilder->isValid();

        if($isValid){

            $checkProfile = $this->accountStore->checkAuth($this->currentAccountBuilder->getData('username'),$this->currentAccountBuilder->getData('password'));
            if($checkProfile===false){
                $this->currentAccountBuilder->addError('loginOuPASSWORDIncorrect', "<strong>nom d'utilisateur</strong> ou <strong>mot de passe</strong> incorrecte");
                $this->view->makeLoginFormPage($this->currentAccountBuilder);

            }
            else{
                $_SESSION['account']=$checkProfile;
                $this->logsStore->create();
                //$_SESSION['count']=intval($this->panierStorage->count($checkProfile->getUsername()));
                $this->showPostList();
            }
        }else{
            $this->view->makeLoginFormPage($this->currentAccountBuilder);
        }
    }

    //Authentification en invite
    public function logInvite(){
          $_SESSION['account']="Invite";
    }



    //formulaire de connexion
    public function connexionPage(){
        $this->view->makeLoginFormPage(new AccountCreation(array()));
    }

    //afficher la liste  post
    public function showPostList(){

        $this->view->makeListPage($this->postStore->readAll());

    }

    //creer un post
    public function makePublishPage(){
      if($this->currentPostBuilder===null){
          $this->currentPostBuilder=new PostCreation();
      }
      $this->view->makePublishForm($this->currentPostBuilder);
    }



    //enregistrer un nouevau post (image)
    public function sauverNouveauPost($data){
      $this->currentPostBuilder = new PostCreation($data);
      //if ($this->currentPostBuilder->isValid()){
      /* On crée le nouveau compte */
          $post = $this->currentPostBuilder->createPost();
          if($post===false)
              $this->view->makePublishForm($this->postBuilder);
          else{/* On l'ajoute en BD */
              $this->postStore->create($post);
              /* On détruit le builder courant */
              $this->showPostList();
              $this->currentPostBuilder = null;

          }
    }

    //se déconnecter
    public function deconnexion(){
        session_unset();
        $this->connexionPage();
    }

    //afficher la liste de compte par l'admin
    public function showAccountList(){
        $this->view->makeListPage($this->accountStore->readAll());
    }

}

?>
