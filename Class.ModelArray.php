<?php


/**
 * Description of Class
 *
 * @author prof
 */
class ModelArray {

    /**
     * choix de l'objet utilis" par getmenu
     * @param $tab
     * @return boolean
     */
    function getTab($tab) {
        require 'd_liensAffichage.php';

        return ${$tab};

    }

}
