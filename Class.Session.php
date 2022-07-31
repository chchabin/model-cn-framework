<?php

/**
 * Classe modélisant la session.
 * Encapsule la superglobale PHP $_SESSION.
 * 
 * @author Baptiste Pesquet
 */
class Session
{

    /**
     * Constructeur.
     * Démarre ou restaure la session
     */
    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
    }

    /**
     * Détruit la session actuelle
     */
    public function detruire():void
    {
        session_destroy();
    }

    /**
     * Ajoute un attribut à la session
     * 
     * @param string $nom Nom de l'attribut
     * @param mixed $valeur Valeur de l'attribut
     */
    public function setAttribut(string $nom, mixed $valeur):void
    {
        $_SESSION[$nom] = $valeur;
    }

    /**
     * Renvoie vrai si l'attribut existe dans la session
     * 
     * @param string $nom Nom de l'attribut
     * @return bool Vrai si l'attribut existe et sa valeur n'est pas vide 
     */
    public function existeAttribut(string $nom): bool
    {
        return (isset($_SESSION[$nom]) && $_SESSION[$nom] != "");
    }

    /**
     * Renvoie la valeur de l'attribut demandé
     *
     * @param string $nom Nom de l'attribut
     * @return bool|string Valeur de l'attribut
     */
    public function getAttribut(string $nom):mixed
    {
        if ($this->existeAttribut($nom)) {
            return $_SESSION[$nom];
        }
        else {
           // throw new Exception("Attribut '$nom' absent de la session");
            return false;
        }
    }
    public function setFlash($message,$type = 'success'): void
    {
        $_SESSION['flash'] = array(
            'message' => $message,
            'type'	=> $type
        );
    }

/*    public function flash(){
        if(isset($_SESSION['flash']['message'])){
            $html = '<div class="alert alert-'.$_SESSION['flash']['type'].'"><p>'.$_SESSION['flash']['message'].'</p></div>';
            $_SESSION['flash'] = array();
            return $html;
        }
    }*/
}