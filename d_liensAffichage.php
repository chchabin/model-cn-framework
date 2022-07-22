<?php

//les liens de choix de graphique
$choix = (object)array(
//les liens du menu
    'liens' => (object)array(
        (object)array(
            'uc' => 'voir',
            'action' => 'valueFramework',
            'designation' => 'Construire modèle'),
        (object)array(
            'uc' => 'voir',
            'action' => 'raz',
            'designation' => 'Raz'),
        (object)array(
            'uc' => 'voir',
            'action' => 'listOperation',
            'designation' => 'Liste des opérations'),
        (object)array(
            'uc' => 'voir',
            'action' => 'afficherTotal',
            'designation' => 'Bilan CN'),
        (object)array(
            'uc' => 'voir',
            'action' => 'afficherPasApas',
            'designation' => 'Comptes pas à pas'),
        (object)array(
            'uc' => 'voir',
            'action' => 'listOperationPasApas',
            'designation' => 'liste des opérations pas à pas'),
    ),
);
$relations = (object)array(
    'Production' => 'R'
    , 'ConsommationIntermediaire' => 'R'
    , 'FiCI' => 'R'
    , 'FiCredit' => 'R'
    , 'RevenusSal' => 'R'
    , 'Consommation' => 'R'
    , 'AchatTitres' => 'R'
    , 'RachatTitres' => 'R'
    , 'AchatImmob' => 'R'
    , 'Depreciation' => 'R'
    , 'RevenusNonSal' => 'R'
    , 'Paiement' => 'M'
    , 'Credit' => 'M'
    , 'RemboursementBq' => 'R'
    , 'FiReglementInt' => 'M'
    , 'EscompteInternational' => 'M'
);
