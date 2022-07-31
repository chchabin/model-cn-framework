<?php
namespace Metier;

class Entreprise
{
//les opérations réelles d'une entreprise
    private string $_achtr;
    private string $_bqLoc;//string Banque
    private string $_colonneTof;
    private array $actif = [
        'Immo' => 0,
        'St' => 0,

        'T' => 0,
        'Rdm' => 0
    ];
    private array $passif = [
        'K' => 0,
        'B' => 0,

        'Sal' => 0,
        'rdm' => 0
    ];
    private array $bilan;

    public function __construct(string $achtr, string $colonneTof)
    {
        $this->_achtr = $achtr;
        $this->_colonneTof = $colonneTof;
        //Bdm est l'établissement financier de Bqm
        if ($this->_achtr == "Rdm" or $this->_achtr == "Bdm") {
            $this->_bqLoc = "Bdm";
        } else {
            $this->_bqLoc = "B";
        }
    }

    public function getNom(): string
    {
        return $this->_achtr;
    }
    public function getBanque(): string
    {
        return $this->_bqLoc;
    }
    public function getActif():array
    {
        return $this->actif;
    }
    public function setActifBlank():void{
        $this->actif = [
            'Immo' => 0,
            'St' => 0,

            'T' => 0,
            'Rdm' => 0
        ];
        if(isset($this->bilan)&&count($this->bilan)>4){
            $lg=count($this->bilan);
            //if($this->getNom()==='M'){var_dump($this->bilan);var_dump(count($this->bilan));}
            for($i=4;$i<$lg;$i++){
                unset($this->bilan[$i]);
            }
        }
    }
    public function setPassifBlank():void{
        $this->passif = [
            'K' => 0,
            'B' => 0,

            'Sal' => 0,
            'rdm' => 0
        ];
    }
    public function getPassif():array
    {
        return $this->passif;
    }

    public function getBilan():array
    {
        $i = 0;
        foreach ($this->actif as $k => $v) {
            $this->bilan[$i] = [$k => $v];
            $i++;
        }

        $i = 0;
        foreach ($this->passif as $k => $v) {
            $this->bilan[$i] += [$k => $v];
            $i++;
        }
        return $this->bilan;
    }


    public function getColonneTOF():string
    {
        return $this->_colonneTof;
    }

    private function majBilan(Entreprise $v): void
    {
        $this->getBilan();
        $v->getBilan();
    }

    private function Immobilisation(float $mont, int $sign):void
    {
        $this->actif['Immo'] += $sign * $mont;
    }

    private function K(float $mont, int $sign):void
    {
        $this->passif['K'] += $sign * $mont;
    }

    private function Stock(float $mont, int $sign):float
    {
        $this->actif['St'] += $sign * $mont;
        return $this->actif['St'];
    }

    private function Banque(float $mont, int $sign):void
    {
        $this->passif['B'] += $sign * $mont;
    }

    private function Client(string $vd, float $mont, int $sign):void
    {
        if (!isset($this->actif[$vd])) {
            $this->actif[$vd] = 0;
        }
        if (!isset($this->passif[strtolower($vd)])) {
            $this->passif[strtolower($vd)] = 0;
        }
        //var_dump($this->actif);
        //var_dump(strtolower($vd));
        $this->actif[$vd] += $sign * $mont;
    }

    private function Fournisseur(string $vd, float $mont, int $sign):void
    {
        if (!isset($this->actif[$vd])) {
            $this->actif[$vd] = 0;
        }
        if (!isset($this->passif[strtolower($vd)])) {
            $this->passif[strtolower($vd)] = 0;
        }

        $this->passif[strtolower($vd)] += $sign * $mont;
    }

    private function Tresorerie(float $mont, int $sign):void
    {
        $this->actif['T'] += $sign * $mont;
    }

    private function Salaire(float $mont, int $sign):void
    {
        $this->passif['Sal'] += $sign * $mont;
    }

    public function Production(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        if ($this->_achtr != 'M') {

            $stock=$this->Stock($mt, 1);
            if ($stock<0){$this->actif['St']=0;}
            $this->Salaire($mt, 1);

            $vd->Salaire($mt, 1);
            $vd->Client($this->_achtr, $mt, 1);

            $this->majBilan($vd);
            //var_dump($this);
        }

    }

    public function CI(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        //Il faut nécessairement (en raison de la prog) que les agents soient des entreprises
       $stock= $this->Stock($mt, 1);
       // if ($stock<0){$this->actif['St']=0;}
        $this->Fournisseur($vd->_achtr, $mt, 1);

        $stock=$vd->Stock($mt * (1 - $txPrf), -1);
      //  if ($stock<0){$vd->actif['St']=0;}
        $vd->Client($this->_achtr, $mt, 1);


        $this->majBilan($vd);
    }

    public function SalairesPayes(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Tresorerie($mt, 1);
        $this->Client($vd->_achtr, $mt, -1);

        $vd->Tresorerie($mt, -1);
        $vd->Salaire($mt, -1);

        $this->majBilan($vd);
    }

    public function RevenusNonSal(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        //Le vendeur est le payeur
        $this->Tresorerie($mt, 1);
        $vd->Tresorerie($mt, -1);

        $this->majBilan($vd);
    }

    public function Consommation(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Tresorerie($mt, -1);
        $this->Salaire($mt, -1);

        $vd->Tresorerie($mt, 1);
        $stock=$vd->Stock($mt * (1 - $txPrf), -1);
        if ($stock<0){$vd->actif['St']=0;}

        $this->majBilan($vd);
    }

    public function Capital(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Immobilisation($mt, 1);
        $this->Fournisseur($vd->_achtr, $mt, 1);

        $stock=$vd->Stock($mt * (1 - $txPrf), -1);
       // if ($stock<0){$this->actif['St']=0;}
        $vd->Client($this->_achtr, $mt, 1);

        $this->majBilan($vd);
    }

    public function Depreciation(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Immobilisation($mt, -1);
        $stock=$this->Stock($mt, 1);
        if ($stock<0){$this->actif['St']=0;}
        $this->majBilan($vd);
    }

    public function CIPaid(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        if ($this->_achtr != $vd->getNom()) {
            $this->Client($vd->getNom(), $mt, -1);
            $this->Tresorerie($mt, 1);
            $vd->Fournisseur($this->_achtr, $mt, -1);
            $vd->Banque($mt, 1);
        }
        else{
            $this->Tresorerie($mt, 1);
            $this->Banque($mt, 1);
        }

        $this->majBilan($vd);
    }

    public function Escompte(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->majBilan($vd);
    }

    public function TitresAchat(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Immobilisation($mt, 1);
        $this->Tresorerie($mt, -1);

        $vd->K($mt, 1);
        $vd->Tresorerie($mt, 1);

        $this->majBilan($vd);
    }

    public function TitresRemboursement(Entreprise $vd, float $mt, float $txPrf = 0):void
    {

        $this->Tresorerie($mt, -1);
        if($vd->getNom()!=$this->getNom()){
            $this->K($mt, -1);
            $vd->Immobilisation($mt, -1);
            $vd->Tresorerie($mt, 1);
        }
        else{
            $this->Banque($mt,-1);
        }



        $this->majBilan($vd);
    }

    public function RemboursementBq(Entreprise $vd, float $mt, float $txPrf = 0):void
    {
        $this->Banque($mt, -1);
        $this->Tresorerie($mt, -1);

        $this->majBilan($vd);
    }

}