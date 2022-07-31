<?php

/**
 * affiche la vue et charge le modèle
 *
 * @author prof
 */
class Controller {

    //variables à passer à la vue dans le controller page à partir du principal
    private array $_vars = array();// valeurs envoyées par la fonction set
    private bool $_affichage=false; // flag pour savoir si la page est déjà affichée
    protected object $_model; //le modele chargé
    protected string $_modelName; //le nom du modele

    // public $Session;

    function __construct()
    {
        //  $this->Session = new Session();
    }

    /**
     * @param string $view
     * @return bool
     */
    function afficher(string $view): bool
    {
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
        return true;
    }

    /**
     * envoie les paramètres de la vue à partir du controleur fils
     * $message="C'est la page d'accueil";
     * $this->set('message',$message);
     * @param  $key
     * @param  $value
     * return _var
     */
    public function set($key, $value = null): void
    {
        if (is_array($key)) {
            $this->_vars += $key;
        } else {
            $this->_vars[$key] = $value;
        }
    }

    /**
     * affiche la page d'erreur
     * @param string $message
     */
    function e404(string $message): void
    {
        header("HTTP/1.0 404 Not Found");
        $this->set('message', $message);
        $this->afficher('v_404.php');
        die();
    }

    /**
     * CHARGE LE MODELE FILS
     * @param $name - nom du controleur
     */

    function loadModel(string $name):void
    {
        if (!isset($this->$name)) {
            $this->_modelName = 'Model' . $name;
            $fichier = "Class.$this->_modelName.php";
            require_once $fichier;
            $mdl = $this->_modelName;
            $this->_model = new $mdl();
        }
    }

    /**
     * appeler un controler depuis une vue,PagesController getmenu
     * @param string $name - nom du model
     * @param string $action
     * @return mixed action du controleur
     */
    public function request(string $name, string $action):mixed
    {
        $name = 'Controller' . $name;
//        var_dump($name);
//        var_dump($action);
        $fichier="Class.$name.php";
        require_once $fichier;
        $ctrl = $name;
        $c = new $ctrl();
//      var_dump(get_class_methods($c));
        return $c->$action();
    }
}
