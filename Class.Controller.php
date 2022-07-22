<?php

/**
 * affiche la vue et charge le modèle
 *
 * @author prof
 */
class Controller {

    //variables à passer à la vue dans le controller page à partir du principal
    private $_vars = array();// valeurs envoyées par la fonction set
    private $_affichage; // flag pour savoir si la page est déjà affichée
    protected $_model; //le modele chargé
    protected $_modelname; //le nom du modele

   // public $Session;

    function __construct(){
      //  $this->Session = new Session();
    }
    /**
     * Aficher les vues dans default
     * @param $view
     * @return boolean
     */    
    function afficher($view) {
        //charge une seule fois la page
        if ($this->_affichage) {
            return false;
        }
        //affecte les variables du set
        extract($this->_vars);
        //stockage contenu
        ob_start();
        require($view);
        $contenu = ob_get_clean();// charge la page et récupère les variables dans contenu
        require 'v_gabarit.php';
//        pas deux fois l'affichage reinitialise
        $this->_affichage = true;
    }
/**
 * envoie les paramètres de la vue à partir du controleur fils
 * $message="C'est la page d'accueil";
 * $this->set('message',$message);
 * @param  $key
 * @param  $value
 * return _var
 */
    public function set($key, $value = null) 
                {
        if (is_array($key)) {
            $this->_vars+= $key;
        } else {
            $this->_vars[$key] = $value;
        }
    }
    
    /**
     * affiche la page d'erreur
     * @param  $message
     */
        function e404($message) {
        header("HTTP/1.0 404 Not Found");
        $this->set('message', $message);
        $this->afficher('v_404.php');
        die();
    }
    /**
     * CHARGE LE MODELE FILS
     * @param $name - nom du controleur
    */

    function loadModel($name) {
        if(!isset($this->$name)){
            $this->_modelname='Model'.$name;
            require_once 'Class.'.$this->_modelname.'.php';
            $mdl=$this->_modelname;
            $this->_model = new $mdl();
        }
    }

    /**
     * appeler un controler depuis une vue,PagesController getmenu
     * @param $name - nom du model
     * @param $action
     * @return mixed action du controleur
     */
    public function request($name, $action) {        
        $name ='Controller'.$name ;
//        var_dump($name);
//        var_dump($action);
        require_once 'Class.'.$name . '.php';
        $ctrl=$name;
        $c = new $ctrl();
//      var_dump(get_class_methods($c));
        return $c->$action();
    }
}
