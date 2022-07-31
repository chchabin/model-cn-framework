<?php

use Metier\ComptaNat;
use Metier\Entreprise;
use Metier\ListAgents;
use Metier\Operation;

class ControllerVoir extends Controller
{
    private ?ListAgents $_agentsList;
    private ComptaNat $_staticList;
    private Operation $_operation;
    private array $operations;

    //Variables
    private array $_params;
    private bool $_mInter=false;

    function __construct()
    {
        parent::__construct();
        $this->_operation = new Operation();
        $this->_agentsList = new ListAgents();
        $this->_staticList = new ComptaNat();
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
        $this->_params[0] =($_REQUEST['mtE1']==='')?60: (float)$_REQUEST['mtE1'];
        $this->_params[1] = ($_REQUEST['mtE2']==='')?25:(float)$_REQUEST['mtE2'];
        $this->_params[2] = ($_REQUEST['txProfit']==='')?0:(float)$_REQUEST['txProfit'];
        $this->_params[3] = ($_REQUEST['mtInvest']==='')?5:(float)$_REQUEST['mtInvest'];
        $this->_params[4]=($_REQUEST['xport']==='')?6:(float)$_REQUEST['xport'];
        $this->_params[5]=($_REQUEST['mport']==='')?7:(float)$_REQUEST['mport'];
        $this->_mInter= isset($_REQUEST['mInter']);
        $this->_operation->Session->setAttribut('params', $this->_params);
        $this->_operation->Session->setAttribut('mInter', $this->_mInter);
        //var_dump($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport);
        $this->loadFramework($this->_params[0], $this->_params[1], $this->_params[2], $this->_params[3],$this->_params[4],$this->_params[5]);
        if(isset($_REQUEST['envoyer'])){$this->afficherTotal();}
        if(isset($_REQUEST['raz'])){$this->raz();}
        if(isset($_REQUEST['pasApas'])){$this->afficherPasApas();}
    }

    public function loadFramework($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport):void
    {
        $profit = new Framework($mtE1, $mtE2, $txProfit, $mtAmortit,$xport,$mport);

        $v1 = $profit->getListProfit();
        //$v2 = $profit->getListInvest();
        $ext=$this->_operationInterbancaireCentrale($profit);
        //var_dump($ext);
       $ope=[...$v1,...$ext];
        //var_dump($ope);
        $this->operations = $ope;

        $this->_operation->Session->setAttribut('operations', $this->operations);
    }
    public function _operationInterbancaireCentrale(Framework $operations):array{
        $solde=$operations->getMtSoldeExt();
        $result=[];
        if($solde<0 && !$this->_mInter){
            $result=$operations->getSoldeNonInter();
        }elseif ($solde<0 && $this->_mInter){
            $result=$operations->getSoldeInter();
        }
        return $result;
    }

    public function getRelations()
    {
        $this->loadModel('Array');
        $_menu = $this->_model;
        return $_menu->getTab('relations');
    }

    public function validerOperation(string $operation, Entreprise $acheteur, Entreprise $vendeur, float $montant)
    {
        return $this->_operation->$operation($acheteur, $vendeur, $montant);
        //var_dump($this->_operation->getAgents()->find($_REQUEST['acheteur'])->getBilan());

    }

    /**
     * @return void
     * @throws Exception
     */
    public function listOperation():void
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
        $this->_operation->Session->setAttribut('number',-1);
        $this->validerFramework();
        if (!isset($this->operations)) {
            $this->operations = $this->_operation->Session->getAttribut('operations');
        }

        $this->afficherOperations([-1]);
    }

    /**
     * @param array $number
     * @param array $operationLine
     * @return void
     * @throws Exception
     */
    public function afficherOperations(array $number, array $operationLine = [-1]):void
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

    /**
     * @return void
     * @throws Exception
     */
    private function validerFramework():void
    {
        $n=$this->_operation->Session->getAttribut('number');
        $lg=count($this->operations);
        if($n==-1){
            $n=$lg-1;
        }
        //var_dump($this->operations);
        for($i=0;$i<$n+1;$i++){
            $ligne=$this->operations[$i];
            $acheteur = $this->_operation->getAgents()->find($ligne['acheteur']);
            $vendeur = $this->_operation->getAgents()->find($ligne['vendeur']);
            $message = $this->validerOperation($ligne['operation'], $acheteur, $vendeur, $ligne['montant']);
        }
/*        foreach ($this->operations as $k => $v) {
            $acheteur = $this->_operation->getAgents()->find($v['acheteur']);
            $vendeur = $this->_operation->getAgents()->find($v['vendeur']);
            $message = $this->validerOperation($v['operation'], $acheteur, $vendeur, $v['montant']);
        }*/
    }

    /**
     * @return void
     * @throws Exception
     */
    public function afficherPasApas():void
    {
            $this->avancer();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function deplacerPasApas():void{
        //var_dump($_SESSION);
        if(isset($_REQUEST['avancer'])){$this->avancer();}
        if(isset($_REQUEST['reculer'])){$this->reculer();}
    }

    /**
     * @return void
     * @throws Exception
     */
    private function avancer():void{

        if (isset($_REQUEST['number'])) {
            $n = $_REQUEST['number'];
            $n++;
        } else {
            $n = 0;
        }
        $this->_operation->Session->setAttribut('number', $n);
        if (!isset($this->operations)) {
            $this->operations = $this->_operation->Session->getAttribut('operations');
        }
        $lg = count($this->operations);
        if ($n < $lg) {
            $v = $this->operations[$n];
            $acheteur = $this->_operation->getAgents()->find($v['acheteur']);
            $vendeur = $this->_operation->getAgents()->find($v['vendeur']);
            $message = $this->validerOperation($v['operation'], $acheteur, $vendeur, $v['montant']);
        } else {
            $n = -1;
        }
        $this->afficherOperations([$n], $this->operations[$n]);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function reculer():void{
        $n = 0;
        if (isset($_REQUEST['number'])&& $_REQUEST['number']!=0) {
            $n = $_REQUEST['number'];
            $n--;
        }

        $this->razBilan();
        $this->_operation->Session->setAttribut('number',$n);
        $this->_params= $this->_operation->Session->getAttribut('params');
        $this->_mInter =$this->_operation->Session->getAttribut('mInter');
        $this->loadFramework($this->_params[0], $this->_params[1], $this->_params[2], $this->_params[3],$this->_params[4],$this->_params[5]);
        $this->validerFramework();
        $this->afficherOperations([$n], $this->operations[$n]);
    }
    public function razBilan(){
        $agentsList=array();
        if ($this->_operation->Session->getAttribut('listeAgent')) {
            $agentsList = $this->_operation->Session->getAttribut('listeAgent');
        }
        foreach ($agentsList as $v){
            $v->setActifBlank();
            $v->setPassifBlank();
            $message=$v->getBilan();
           // var_dump($v->getBilan());
        }
        $this->_operation->Session->setAttribut('listeAgent',null);
       // var_dump($agentsList[3]->getBilan());
        $this->_operation->Session->setAttribut('listeAgent',$agentsList);
        $this->_operation->RazCN();
        /*if ($this->_operation->Session->existeAttribut('cn')) {

            $this->_staticList = $this->_operation->Session->getAttribut('cn');
            var_dump($this->_operation->Session->getAttribut('cn'));
            var_dump($this->_staticList->getActif_TEE());
        }*/

    }

    /**
     * @return void
     * @throws Exception
     */
    public function listOperationPasApas():void
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
    private function compteurPasApas(){

    }

}


