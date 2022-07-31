<?php


/**
 * Description of Class
 *
 * @author prof
 */
class ModelArray {

    /**
     * choix de l'objet utilis" par getmenu
     * @param string $tab
     * @return object
     */
    function getTab(string $tab):object {
        require 'd_liensAffichage.php';

        return ${$tab};

    }

}
