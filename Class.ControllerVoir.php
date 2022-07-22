<?php

use Metier\ComptaNat;
use Metier\ListAgents;
use Metier\Operation;

class ControllerVoir extends Controller
{
    /**
     * @var ModelArray
     * @var ListAgents
     */
    private ModelArray $_menu;
    private ?ListAgents $_agentsList;
    private ComptaNat $_staticList;
    private Operation $_operation;
    private array $operations;
    private array $lineOperations;

    function __construct()
    {
        parent::__construct();
        $this->_operation = new Operation();
        $this->_agentsList = new ListAgents();
        $this->_staticList = new ComptaNat();
       // $this->_mlist = new Modellist();

    }

    public function accueil()
    {
        $this->raz();
        $vue = 'v_formSaisieFramework.php';
        $this->afficher($vue); //affichage dans la vue
    }

    /**
     * @throws exception
     */
    public function loadParam(){
        //var_dump($_REQUEST);
        $mtE1 =empty($_REQUEST['mtE1'])?60: (float)$_REQUEST['mtE1'];
        $mtE2 = empty($_REQUEST['mtE2'])?25:(float)$_REQUEST['mtE2'];
        $txProfit = empty($_REQUEST['txProfit'])?100:(float)$_REQUEST['txProfit'];
        $mtAmortit = empty($_REQUEST['mtInvest'])?5:(float)$_REQUEST['mtInvest'];
        $xport=empty($_REQUEST['xport'])?6:(float)$_REQUEST['xport'];
        $mport=empty($_REQUEST['mport'])?7:(float)$_REQUEST['mport'];
        $mInter= isset($_REQUEST['mInter']);
        $this->loadFramework($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport);
        if(isset($_REQUEST['envoyer'])){$this->afficherTotal();}
        if(isset($_REQUEST['raz'])){$this->raz();}
        if(isset($_REQUEST['pasApas'])){$this->afficherPasApas();}
    }



    public function getRelations()
    {
        $this->loadModel('Array');
        $this->_menu = $this->_model;
        return $this->_menu->getTab('relations');
    }

    public function validerOperation(string $operation, \Metier\Entreprise $acheteur, \Metier\Entreprise $vendeur, float $montant)
    {
        return $this->_operation->$operation($acheteur, $vendeur, $montant);
        //var_dump($this->_operation->getAgents()->find($_REQUEST['acheteur'])->getBilan());

    }

    public function listOperation()
    {
        if ($this->_operation->Session->existeAttribut('operations')) {
            $this->operations = $this->_operation->Session->getAttribut('operations');
        }
        $this->set('lesOperations', $this->operations ?? null);
        $this->set('lesRelations', $this->getRelations());

    }

    /**
     * @throws exception
     */
    public function afficherTotal()
    {
        $this->validerFramework();
        if (!isset($this->operations)) {
            $this->operations = $this->_operation->Session->getAttribut('operations');
        }

        $this->afficherOperations([-1]);
    }

    public function afficherOperations(array $number, array $operationLine = [-1])
    {
        //$nomAgents=[];
        //var_dump($_SESSION);
        if ($this->_operation->Session->getAttribut('listeAgent')) {
            $this->operations = $this->_operation->Session->getAttribut('listeAgent');
            foreach ($this->operations as $k => $v) {
                $this->_agentsList->add($v);
                //$nomAgents[]=$v->getNom();
            }
        }
        //var_dump($this->_agentsList->find('E2')->getBilan());
        // var_dump($this->_agentsList->find('E2')->getPassif());
        $listAgents = $this->_agentsList->all();
        $this->set('listAgents', $listAgents);
        //$this->set('nomAgents', $nomAgents);

        if ($this->_operation->Session->existeAttribut('cn')) {
            $this->_staticList = $this->_operation->Session->getAttribut('cn');
            $this->set('CN', $this->_staticList);
        }
        //var_dump($this->_operation->Session->getAttribut('cn'));
        //$number=!isset($number[0])?[-1]:$number;
        if ($number[0] != -1) {
            $this->set('operations', $operationLine);
            $this->set('relations', $this->getRelations());
            $this->listOperationPasApas();
        }
        else{$this->listOperation();}
        $this->set('number', $number[0]);

        //$this->set('number',$n+1);
        //var_dump($listAgents);
        $vue = 'v_tableaux.php';
        $this->afficher($vue);
    }

    public function raz()
    {
        $this->_operation->Session->detruire();
        $vue = 'v_formSaisieFramework.php';
        $this->afficher($vue); //affichage dans la vue
    }

    private function validerFramework()
    {
        foreach ($this->operations as $k => $v) {
            $acheteur = $this->_operation->getAgents()->find($v['acheteur']);
            $vendeur = $this->_operation->getAgents()->find($v['vendeur']);
            $message = $this->validerOperation($v['operation'], $acheteur, $vendeur, $v['montant']);
        }
    }

    public function loadFramework($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport):void
    {
        $profit = new Framework($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport);

        $v1 = $profit->getListProfit();
        $v2 = $profit->getListInvest();
        $this->operations = $v1;

        $this->_operation->Session->setAttribut('operations', $this->operations);

    }

    public function afficherPasApas()
    {
        if (isset($_REQUEST['number'])) {
            $n = $_REQUEST['number'];
            $n++;
        } else {
            $n = 0;
        }
        $this->_operation->Session->setAttribut('number', $n);
        if (!isset($this->lineOperations)) {
            $this->lineOperations = $this->_operation->Session->getAttribut('operations');
        }

        $lg = count($this->lineOperations);
        if ($n < $lg) {
            $v = $this->lineOperations[$n];
            $acheteur = $this->_operation->getAgents()->find($v['acheteur']);
            $vendeur = $this->_operation->getAgents()->find($v['vendeur']);
            $message = $this->validerOperation($v['operation'], $acheteur, $vendeur, $v['montant']);
        } else {
            $n = -1;
        }
        $this->afficherOperations([$n], $this->lineOperations[$n]);
    }

    public function listOperationPasApas()
    {
        $this->operations = array();
        $n = $this->_operation->Session->getAttribut('number');
        $n = !$n ? 0 : $n;
        $operationsLine = $this->_operation->Session->getAttribut('operations');
        $lg = count($operationsLine);
        if ($n == $lg) {
            $n--;
        }
        for ($i = 0; $i < $n + 1; $i++) {
            $this->operations[$i] = $operationsLine[$i];
        }

        $this->set('lesOperations', $this->operations ?? null);
        $this->set('lesRelations', $this->getRelations());


    }

}


