<?php

namespace Metier;

use Exception;
use Session;

class Operation
{
    private ListAgents $_agentsList;
    private ListOperations $_operationList;
    private ComptaNat $_CN;
    public Session $Session;

    /**
     * @throws Exception
     */
    function __construct()
    {
        $this->Session = new Session();
        $this->_agentsList = new ListAgents();
        $this->InitialiseAgent();
        $this->_operationList = new ListOperations();
        $this->InitialiseOperation();
        $this->_CN = new ComptaNat();
        $this->getAgents();

    }

    private function InitialiseAgent(): void
    {
        $this->_agentsList->add(new Entreprise('E1', 'E'));
        $this->_agentsList->add(new Entreprise('E2', 'E'));
        $this->_agentsList->add(new Entreprise('E3', 'E')); // Stat
        $this->_agentsList->add(new Entreprise('M', 'M'));
        $this->_agentsList->add(new Entreprise('Rdm', 'Ext'));
        $this->_agentsList->add(new Banque('B'));
        $this->_agentsList->add(new Banque('Bdm'));
    }

    private function InitialiseOperation(): void
    {
        $this->_operationList->add('Production');
        $this->_operationList->add('ConsommationIntermediaire');
        $this->_operationList->add('FiCI');
        $this->_operationList->add('RevenusSal');
        $this->_operationList->add('Consommation');
        $this->_operationList->add('AchatTitres');
        $this->_operationList->add('RachatTitres');
        $this->_operationList->add('AchatImmob');
        $this->_operationList->add('Depreciation');
        $this->_operationList->add('RevenusNonSal');
        $this->_operationList->add('RemboursementBq');
        $this->_operationList->add('Paiement');
        $this->_operationList->add('Credit');
        $this->_operationList->add('ReEscompte');
        $this->_operationList->add('RazCN');
        // $this->_operationList->add('EscompteInternational');
    }

    public function getOperation(): object
    {
        return $this->_operationList;
    }

    /**
     * @return object
     * @throws Exception
     */
    public function getAgents(): object
    {
        if ($this->Session->existeAttribut('listeAgent')) {
            $this->_agentsList->raz();
            $list = $this->Session->getAttribut('listeAgent');
            foreach ($list as $k => $v) {
                $this->_agentsList->add($v);
            }
        } else {
            $this->Session->setAttribut('listeAgent', $this->_agentsList->all());
        }
        //var_dump($this->_agentsList->all());
        return $this->_agentsList;
    }

    /**
     * @return object
     * @throws Exception
     */
    public function getCN(): object
    {
        if ($this->Session->existeAttribut('cn')) {
            $this->_CN = $this->Session->getAttribut('cn');
        }
        return $this->_CN;
    }

    public function RazCN()
    {
        $this->_CN->setActifTeeBlank();
        $this->_CN->setPassifTeeBlank();;
        $this->_CN->setActifTofBlank();
        $this->_CN->setPassifTofBlank();
        $this->Session->setAttribut('cn', $this->_CN);
    }

    /**
     * Le vendeur est M et l'acheteur E
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function Production(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = "Le vendeur doit être M";
        if ($vdr->getNom() == 'M') {
            $ach->Production($vdr, $mt);

            //  CHARGEMENT DE LA SESSION
            $this->getCN();
            $this->_CN->TeeProduction($mt);
            $this->_CN->TeeStock($mt);
            $this->_CN->getBilan_TEE();
            // SAUVEGARDE DE LA SESSION
            $this->Session->setAttribut('cn', $this->_CN);
            $validation = true;
        }
        return $validation;
    }

    /**
     * Les agents E et Rdm
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @param float $txPrf
     * @return string
     * @throws Exception
     */
    public function ConsommationIntermediaire(Entreprise $ach, Entreprise $vdr, float $mt, float $txPrf = 0): string
    {
        $validation = "M ne peut pas figurer dans cette opération";
        if ($vdr->getNom() != 'M' && $ach->getNom() != 'M') {
            $ach->CI($vdr, $mt);
            $this->getCN();
            if ($ach->getNom() == "Rdm") {
                $this->getCN();
                $this->_CN->TeeExport($mt * (1 - $txPrf));
                $this->_CN->TeeStock(-1 * $mt * (1 - $txPrf));
                $this->_CN->getBilan_TEE();
                $this->Session->setAttribut('cn', $this->_CN);
                $validation = true;
            }
            if ($vdr->getNom() == "Rdm") {
                $this->_CN->TeeImport($mt * (1 - $txPrf));
                $this->_CN->TeeStock($mt * (1 - $txPrf));
                $validation = true;
            } elseif ($ach->getNom() != "Rdm") {
                $this->_CN->TeeCInterm($mt);
                $this->_CN->TeeProduction($mt);
                $validation = true;
            }

            $this->_CN->getBilan_TEE();
            // SAUVEGARDE DE LA SESSION
            $this->Session->setAttribut('cn', $this->_CN);
        }
        return $validation;
    }

    /**
     *plante avec M
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function FiCI(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = "M ne peut pas figurer comme acheteur ou B ne peut pas figurer comme vendeur";
        if (!($vdr->getNom() != 'M' xor $ach->getNom() != 'M') && $vdr->getNom() != 'Bfi' || $vdr->getNom() == 'M') {
            $ach->CIPaid($vdr, $mt);
            $validation = true;
        }
        $this->getCN();
        $this->_CN->actif_TOF($ach->getColonneTOF(), 0, -1);
        $this->_CN->actif_TOF($vdr->getColonneTOF(), 0, 1);
        $this->_CN->getBilan_TOF();
        $this->Session->setAttribut('cn', $this->_CN);
        return $validation;
    }

    /**
     * le financement du crédit se fait sans mouvement de stock, les acheteurs sont les vendeurs
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function FiCredit(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        //$validation = "les acheteurs sont les vendeurs";
        //if ($vdr->getNom() == $ach->getNom()) {
        $ach->CIPaid($vdr, $mt);
        $validation = true;
        //}
        $this->getCN();
        $this->_CN->actif_TOF($ach->getColonneTOF(), 0, 1);
        $this->_CN->passif_TOF($vdr->getColonneTOF(), 0, 1);
        $this->_CN->getBilan_TOF();
        $this->Session->setAttribut('cn', $this->_CN);
        return $validation;
    }

    /**
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function RevenusSal(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = 'Le vendeur ne peut pas être M';
        if ($vdr->getNom() != 'M') {
            $ach->SalairesPayes($vdr, $mt);

            $this->getCN();
            $this->_CN->TeeRsal($mt);
            $this->_CN->TeeRsalEM($mt);
            $this->_CN->getBilan_TEE();
            $this->Session->setAttribut('cn', $this->_CN);
            $validation = true;
        }
        return $validation;
    }

    /**
     * acheteur M vendeur E (Rdm est une CI)
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @param float $txPrf
     * @return string
     * @throws Exception
     */
    public function Consommation(Entreprise $ach, Entreprise $vdr, float $mt, float $txPrf = 0): string
    {
        $validation = "L'acheteur doit être M";
        if ($ach->getNom() == 'M') {
            $ach->Consommation($vdr, $mt);
            // var_dump($vdr);

            $this->getCN();
            $this->_CN->TeeCfinale($mt);
            $this->_CN->TeeStock(-1 * $mt * (1 - $txPrf));
            $this->_CN->getBilan_TEE();
            $this->Session->setAttribut('cn', $this->_CN);
            $validation = true;
        }
        return $validation;
    }

    /**
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function AchatTitres(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = "Le vendeur ni l'acheteur ne peuvent être B";
        if ($vdr->getNom() != 'Bfi' && $ach->getNom() != 'Bfi') {
            $ach->TitresAchat($vdr, $mt);
            $validation = true;

            $this->getCN();
            $this->_CN->actif_TOF($ach->getColonneTOF(), $mt, 1);
            $this->_CN->passif_TOF($vdr->getColonneTOF(), $mt, 1);
            $this->_CN->getBilan_TOF();
            $this->Session->setAttribut('cn', $this->_CN);
        }
        return $validation;
    }

    /**
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function RachatTitres(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = "B ne peut pas figurer comme acheteur";
        if ($vdr->getNom() != 'Bfi') {
            $ach->TitresRemboursement($vdr, $mt);

            $validation = true;

            $this->getCN();
            $this->_CN->passif_TOF($ach->getColonneTOF(), $mt, -1);
            $this->_CN->actif_TOF($vdr->getColonneTOF(), $mt, -1);
            $this->_CN->getBilan_TOF();
            $this->Session->setAttribut('cn', $this->_CN);
        }
        return $validation;
    }

    /**
     * Voir le cas Rdm et import
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @param float $txPrf
     * @return bool
     * @throws Exception
     */
    public function AchatImmob(Entreprise $ach, Entreprise $vdr, float $mt, float $txPrf = 0): bool
    {

        $ach->Capital($vdr, $mt);

        $this->getCN();
        $this->_CN->TeeFbcf($mt);
        $this->_CN->TeeStock(-1 * $mt * (1 - $txPrf));
        $this->_CN->getBilan_TEE();
        $this->Session->setAttribut('cn', $this->_CN);

        $validation = true;

        return $validation;
    }

    /**
     * La dépréciation ne concerne que l'acheteur
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return bool
     * @throws Exception
     */
    public function Depreciation(Entreprise $ach, Entreprise $vdr, float $mt): bool
    {
        $ach->Depreciation($vdr, $mt);

        $this->getCN();
        $this->_CN->TeeProduction($mt);
        $this->_CN->TeeStock($mt);
        $this->_CN->getBilan_TEE();
        $this->Session->setAttribut('cn', $this->_CN);
        $validation = true;

        return $validation;
    }

    /**Voir le cas Rdm et import
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return bool
     * @throws Exception
     */
    public function RevenusNonSal(Entreprise $ach, Entreprise $vdr, float $mt): bool
    {
        $ach->RevenusNonSal($vdr, $mt);
        $this->getCN();
        if ($vdr->getNom() != 'M') {
            $this->_CN->TeeRnsalEE($mt);
            if ($ach->getNom() != 'M') {
                $this->_CN->TeeRnsalER($mt);
            } else {
                $this->_CN->TeeRnsalME($mt);
            }
        } else {
            $this->_CN->TeeRnsalME($mt);
            if ($ach->getNom() != 'M') {
                $this->_CN->TeeRnsalER($mt);
            } else {
                $this->_CN->TeeRnsalMR($mt);
            }
        }
        $this->_CN->getBilan_TEE();
        $this->Session->setAttribut('cn', $this->_CN);
        $validation = true;

        return $validation;
    }

    /**
     * Le remboursement ne concerne que l'acheteur
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return bool
     * @throws Exception
     */
    public function RemboursementBq(Entreprise $ach, Entreprise $vdr, float $mt): bool
    {

        $ach->RemboursementBq($vdr, $mt);
        $validation = true;

        $this->getCN();
        $this->_CN->actif_TOF($ach->getColonneTOF(), 0, -1);
        $this->_CN->actif_TOF($vdr->getColonneTOF(), 0, 1);
        $this->_CN->getBilan_TOF();
        $this->Session->setAttribut('cn', $this->_CN);

        return $validation;


    }

    /**
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return bool
     * @throws Exception
     */
    public function Paiement(Entreprise $ach, Entreprise $vdr, float $mt): bool
    {
        $bqv = $this->_agentsList->find($vdr->getBanque());
        $bqa = $this->_agentsList->find($ach->getBanque());

        if ($ach->getBanque() == $vdr->getBanque()) {
            $bqv->BanqueFin_Passif($vdr->getNom(), $mt);
            $bqa->BanqueFin_Passif($ach->getNom(), -1 * $mt);
        } elseif ($vdr->getNom() == 'Rdm' || $ach->getNom() == 'Rdm') {
            $bqa->BanqueFin_Passif('BC', $mt);
            if ($ach->getNom() == 'Rdm') {
                $bqa->BanqueFin_Passif('Bfi', -1 * $mt);
            } else {
                $bqa->BanqueFin_Passif($ach->getNom(), -1 * $mt);
            }

            $bqv->BanqueFin_Passif($vdr->getNom(), $mt);
            $bqv->BanqueFin_Actif('BC', $mt);
        }

        $this->getCN();
        $this->_CN->actif_TOF($ach->getColonneTOF(), $mt, -1);
        $this->_CN->actif_TOF($vdr->getColonneTOF(), $mt, 1);
        $this->_CN->getBilan_TOF();
        $this->Session->setAttribut('cn', $this->_CN);

        $validation = true;

        return $validation;
    }

    /**
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return bool
     * @throws Exception
     */
    public function Credit(Entreprise $ach, Entreprise $vdr, float $mt): bool
    {
        $bqv = $this->_agentsList->find($vdr->getBanque());
        $bqa = $this->_agentsList->find($ach->getBanque());
        if ($bqa == $bqv) {
            $bqa->BanqueFin_Actif($ach->getNom(), $mt);
            $bqa->BanqueFin_Passif($vdr->getNom(), $mt);

            $ptof = $ach->getColonneTOF();
            $atof = $vdr->getColonneTOF();
        } elseif ($vdr->getNom() == 'Rdm') {

            $bqa->BanqueFin_Passif('BC', $mt);
            $bqa->BanqueFin_Actif($ach->getNom(), $mt);

            $bqv->BanqueFin_Passif('Rdm', $mt);
            $bqv->BanqueFin_Actif('BC', $mt);

            $ptof = $ach->getColonneTOF();
            $atof = 'BC';
        } else {

            $bqv->BanqueFin_Passif($vdr->getNom(), $mt);
            $bqv->BanqueFin_Actif('BC', $mt);

            $bqa->BanqueFin_Passif('BC', $mt);
            $bqa->BanqueFin_Actif('Rdm', $mt);

            $ptof = 'BC';
            $atof = $vdr->getColonneTOF();
        }

        $this->getCN();
        $this->_CN->passif_TOF($ptof, $mt, 1);
        $this->_CN->actif_TOF($atof, $mt, 1);
        $this->_CN->getBilan_TOF();
        $this->Session->setAttribut('cn', $this->_CN);

        $validation = true;

        return $validation;
    }

    /**
     * Échange interbancaire entre BC et une autre banque Bq ou Brm
     * Si BC est débiteur envers Bqm il est acheteur de titres de Brm
     * BC vends des titres à Brm
     * L’émetteur de titre est l’État soit E3
     * @param Banque $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     * @throws Exception
     */
    public function ReEscompte(Entreprise $ach, Entreprise $vdr, float $mt): string
    {
        $validation = "Acht = E";

        $bqa = $this->_agentsList->find($ach->getBanque());
        if ($ach->getColonneTOF() == 'E') {

            $bqa->BanqueFin_Actif($ach->getNom(), -$mt);
            $bqa->BanqueFin_Actif('BC', $mt);

            $this->getCN();
            $this->_CN->passif_TOF($ach->getColonneTOF(), $mt, -1);
            $this->_CN->passif_TOF('BC', $mt, 1);
            $this->_CN->getBilan_TOF();
            $this->Session->setAttribut('cn', $this->_CN);

            $validation = true;
        }
        return $validation;
    }

    /**
     * Situation de monnaie internationale
     * le rdm vends des titres et paie l'interbancaire pour le règlement
     * @param Entreprise $ach
     * @param Entreprise $vdr
     * @param float $mt
     * @return string
     */
    /* public function EscompteInternational(Entreprise $ach, Entreprise $vdr, float $mt): string
     {

         $validation = "Acht = Bdm";
         if ($ach->getBanque() == 'Bdm') {
             $bqv = $this->_agentsList->find($vdr->getBanque());
             $bqa=$this->_agentsList->find($ach->getBanque());
             $bqv->BanqueFin_Actif($vdr->getNom(), -1*$mt);
             $bqv->BanqueFin_Actif('BC', $mt);

             $bqa->BanqueFin_Actif('BC',$mt);
             $bqa->BanqueFin_Passif('BC', $mt);

             $this->getCN();
             $this->_CN->actif_TOF('BC', $mt, 1);
             $this->_CN->passif_TOF($vdr->getColonneTOF(), $mt, 1);
             $this->_CN->getBilan_TOF();
             $this->Session->setAttribut('cn', $this->_CN);

             $validation = true;
         }

         return $validation;
     }*/

}