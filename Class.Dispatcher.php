<?php

/**
 * Charge les controlleur passés en url
 *
 * @author prof
 */
class Dispatcher {

    private string $_uc;//controleur
    private string $_action;//méthode du controleur
    private array $_params = array();//paramètres associés à l'action

    /**
 * Gestion des url
 * /index.php?uc=voirGraphique&action=creerGraphique&params=xxx
 * sans index force 
 * /index.php?uc=voirGraphique&action=accueil
 */
    function __construct() {
        //Gestion de l'url
        $this->loadRequest();

        $controller = $this->loadController();

        //test si la méthode existe dans le contrôleur
        if (!in_array($this->_action, get_class_methods($controller))) {
            $this->error("Le controller $this->_uc n'a pas de méthode " . $this->_action);
        }
        //appel de la méthode du contrôleur
        call_user_func_array(array($controller, $this->_action), array( $this->_params));

    }

    /**
     * routage de l'URL
     */
    function loadRequest():void{
        $_urlValues = $_REQUEST;
        if (!isset($_urlValues['uc'])) {
            $this->_uc = 'voir';
        } else {
            $this->_uc = $_urlValues['uc'];
        }
        if (!isset($_urlValues['action'])) {
            $this->_action = 'accueil';
        } else {
            $this->_action = $_urlValues['action'];
        }

    }

    /**
     * @return object
     */
    function loadController():object {
        $nameCtrl=ucfirst($this->_uc);
        $_ucname ='Controller'.$nameCtrl;
        $name="Class.$_ucname.php";
        require $name;
        return new $_ucname();
    }

    function error(string $message):void {
        $controller = new Controller();
        $controller->e404($message);
    }

}
