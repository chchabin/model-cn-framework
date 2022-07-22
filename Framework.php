<?php

class Framework
{
    private float $mtE1;
    private float $mtE2;
    private float $txProfit;
    private float $mtAmortit;
    private float $xport;
    private float $mport;

    public function __construct(float $mtE1, float $mtE2, float $txProfit, float $mtAmortit = 0,float $mtxport,float $mtmport)
    {

        if (($txProfit >= 0 && $txProfit <= 100) && ($mtE1 >= $mtE2)) {
            $this->mtE1 = $mtE1;
            $this->mtE2 = $mtE2;
            $this->txProfit = $txProfit / 100;
            $this->mtAmortit = $mtAmortit;
            $this->xport=$mtxport;
            $this->mport=$mtmport;
        }
    }

    public function getListProfit()
    {
        return array(
            array(
                'operation' => 'Production',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE1),
            array(
                'operation' => 'Production',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2) ,
           array(
                'operation' => 'ConsommationIntermediaire',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
             array(
                'operation' => 'FiCI',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'AchatTitres',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit),
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE1 * $this->txProfit),


            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit),
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit * -1),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 * $this->txProfit * -1),
            /****************************************/
            array(
                'operation' => 'Production',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
           array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),

            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'AchatTitres',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit * -1),
            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            /*****************AMORTISSEMENT***********************/
          array(
                'operation' => 'Production',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE1),
            array(
                'operation' => 'Production',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit),
            array(
                'operation' => 'Depreciation',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),

            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * 2),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * 2),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit),
//            Remboursement du crédit des ménages
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit * -1),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit * -1),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit - $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit - $this->mtAmortit),
//            Accord de crédit pour écouler la production
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtAmortit),
//            Expropriation des moyens de production par vente de titres (si oui → comptabiliser dans CN)
            array(
                'operation' => 'AchatTitres',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit),
//            Pour ne pas avoir de stock (cf cn) on passe le profit dual en immobilisation
            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtAmortit),
            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit*2),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtAmortit * -1),
            //Relations internationales
            array(
                'operation'=> 'ConsommationIntermediaire',
                'acheteur'=> 'E1',
                'vendeur'=> 'Rdm',
                'montant'=> $this->mport * (1 - $this->txProfit)
            ),
            array(
                'operation'=> 'FiCI',
                'acheteur'=> 'Rdm',
                'vendeur'=> 'E1',
                'montant'=> $this->mport * (1 - $this->txProfit)
            ),
            array(
                'operation'=> 'Credit',
                'acheteur'=> 'E1',
                'vendeur'=> 'Rdm',
                'montant'=> $this->mport * (1 - $this->txProfit)
            ),
            array(
                'operation'=> 'ConsommationIntermediaire',
                'acheteur'=> 'Rdm',
                'vendeur'=> 'E1',
                'montant'=> $this->xport * (1 - $this->txProfit)
            ),
            array(
                'operation'=> 'FiCI',
                'acheteur'=> 'E1',
                'vendeur'=> 'Rdm',
                'montant'=> $this->xport * (1 - $this->txProfit)
            ),
            array(
                'operation'=> 'Credit',
                'acheteur'=> 'Rdm',
                'vendeur'=> 'E1',
                'montant'=> $this->xport * (1 - $this->txProfit)
            ),

        );



    }

    public function getListInvest()
    {
        return array(
            array(
                'operation' => 'Production',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCredit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit * 2),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit * 2),
            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),

            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit * -1),

        );
    }

    public function getListAmort()
    {
        return array(
            array(
                'operation' => 'Production',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE1),
            array(
                'operation' => 'Production',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2),
            array(
                'operation' => 'Depreciation',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit + $this->mtAmortit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit * -1),
            array(
                'operation' => 'AchatImmob',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'FiCI',
                'acheteur' => 'E1',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'E1',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'M',
                'montant' => $this->mtE2),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * (1 + $this->txProfit)),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * (1 + $this->txProfit)),
            array(
                'operation' => 'AchatTitres',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Credit',
                'acheteur' => 'M',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'RemboursementBq',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => $this->mtE2 * $this->txProfit * 2),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E2',
                'vendeur' => 'E2',
                'montant' => ($this->mtE2 * $this->txProfit * 2 + $this->mtE2) * -1),
            array(
                'operation' => 'RevenusSal',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 - $this->mtE2 - ($this->mtE2 * $this->txProfit)),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE1 - $this->mtE2 - ($this->mtE2 * $this->txProfit) * 2),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'E1',
                'vendeur' => 'M',
                'montant' => $this->mtE2 * $this->txProfit),
            array(
                'operation' => 'Consommation',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 - ($this->mtE2 * $this->txProfit + $this->mtAmortit) + $this->mtE1 - $this->mtE2 - ($this->mtE2 * $this->txProfit)),
            array(
                'operation' => 'Paiement',
                'acheteur' => 'M',
                'vendeur' => 'E1',
                'montant' => $this->mtE1 - ($this->mtE2 * $this->txProfit + $this->mtAmortit) + $this->mtE1 - $this->mtE2 - ($this->mtE2 * $this->txProfit)),
            array(
                'operation' => 'Credit',
                'acheteur' => 'E1',
                'vendeur' => 'E1',
                'montant' => ($this->mtE1 - $this->mtE2 - ($this->mtE2 * $this->txProfit) * 2) * -1),
        );
    }
}