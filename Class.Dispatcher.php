<?php

/**
 * Charge les controlleur passés en url
 *
 * @author prof
 */
class Dispatcher {

    private $_uc;//controleur
    private $_ucname;//nom du controleur
    private $_action;//méthode du controleur
    private $_params = array();//paramètres associés à l'action
    private $_urlValues;

/**
 * Gestion des url
 * /index.php?uc=voirGraphique&action=creerGraphique&params=xxx
 * sans index force 
 * /index.php?uc=voirGraphique&action=accueil
 */
    function __construct() {
        //Gestion de l'url
        $this->loadRequete();

        $controleur = $this->loadControleur();
        
        //test si la méthode existe dans le controleur
        if (!in_array($this->_action, get_class_methods($controleur))) {
            $this->error('Le controller ' . $this->_uc . ' n\'a pas de méthode ' . $this->_action);
        }
        //appel de la méthode du controleur
        call_user_func_array(array($controleur, $this->_action), array( $this->_params));

    }

    /**
     * routage de l'URL
     */
    function loadRequete(){
        $this->_urlValues = $_REQUEST;
        if (!isset($this->_urlValues['uc'])) {
            $this->_uc = 'voir';
        } else {
            $this->_uc = $this->_urlValues['uc'];
        }
        if (!isset($this->_urlValues['action'])) {
            $this->_action = 'accueil';
        } else {
            $this->_action = $this->_urlValues['action'];
        }

    }
    /**
     * Appel du controleur
     * @return mixed
     */
    function loadControleur() {
        $this->_ucname='Controller'.$this->_uc;
        require "Class.$this->_ucname.php";
        $ctrl=$this->_ucname;
        return new $ctrl();
    }

    function error($message) {
        $controller = new Controller();
        $controller->e404($message);
    }

}
