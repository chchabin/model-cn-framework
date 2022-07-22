<?php
namespace Metier;
class ComptaNat
{
    private string $_achtr = 'Cn';
    private array $actif_TEE = [
        'E' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ],
        'M' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ],
        'Ext' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ]
    ];
    private array $passif_TEE = [
        'e' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ],
        'm' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ],
        'ext' => [
            'prd' => 0,
            'ci' => 0,
            'va' => 0,
            'sal' => 0,
            'rns' => 0,
            'rdb' => 0,
            'c' => 0,
            'e' => 0,
            'st' => 0,
            'fbcf' => 0,
            'solde' => 0
        ]
    ];
    private array $actif_TOF = [
        'BC' => 0,
        'E' => 0,
        'M' => 0,
        'Ext' => 0
    ];
    private array $passif_TOF = [
        'bc' => 0,
        'e' => 0,
        'm' => 0,
        'ext' => 0
    ];
    private array $bilan_TEE;
    private array $bilan_TOF;

    public function getNom(): string
    {
        return $this->_achtr;
    }

    public function getActif_TEE(): array
    {
        return $this->actif_TEE;
    }

    public function getBilan_TEE()
    {
        $i = 0;
        foreach ($this->actif_TEE as $k => $v) {
            if ($k == 'E') {
                foreach ($v as $k2 => $l) {
                    $this->bilan_TEE[$i] = [$k2 => $l];
                    $i = ($i < 10) ? ($i + 1) : 0;
                }

            } else {
                foreach ($v as $k2 => $l) {
                    $this->bilan_TEE[$i] += [$k2 . $k => $l];
                    $i = ($i < 10) ? ($i + 1) : 0;
                }

            }
        }
        //var_dump($this->bilan_TEE);
        $i = 0;
        foreach ($this->passif_TEE as $k => $v) {
            if ($k == 'E') {
                foreach ($v as $k2 => $l) {
                    $this->bilan_TEE[$i] = [$k2 => $l];
                    $i = ($i < 10) ? ($i + 1) : 0;
                }
            } else {
                foreach ($v as $k2 => $l) {
                    $this->bilan_TEE[$i] += [$k2 . $k => $l];
                    $i = ($i < 10) ? ($i + 1) : 0;
                }
            }
        }

        //var_dump($this->bilan_TEE);
        return $this->bilan_TEE;
    }

    public function getBilan_TOF()
    {
        $i = 0;
        foreach ($this->actif_TOF as $k => $v) {
            $this->bilan_TOF[$i] = [$k => $v];
            $i++;
        }

        $i = 0;
        foreach ($this->passif_TOF as $k => $v) {
            $this->bilan_TOF[$i] += [$k => $v];
            $i++;
        }
        return $this->bilan_TOF;
    }

    public function TeeProduction(float $mt)
    {
        $this->passif_TEE[strtolower('E')]['prd'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeStock(float $mt)
    {
        $this->actif_TEE['E']['st'] += $mt;
        if($this->actif_TEE['E']['st']<0){$this->actif_TEE['E']['st']=0;}
        $this->getBilan_TEE();
    }

    public function TeeExport(float $mt)
    {
        $this->actif_TEE['Ext']['prd'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeImport(float $mt)
    {
        $this->passif_TEE[strtolower('Ext')]['prd'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeCInterm(float $mt)
    {
        $this->actif_TEE['E']['ci'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRsal(float $mt)
    {
        $this->actif_TEE['E']['sal'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRsalEM(float $mt)
    {
        $this->passif_TEE[strtolower('M')]['sal'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRnsalEE(float $mt)
    {
        $this->actif_TEE['E']['rns'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRnsalER(float $mt)
    {
        $this->passif_TEE[strtolower('E')]['rns'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRnsalME(float $mt)
    {
        $this->actif_TEE['M']['rns'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeRnsalMR(float $mt)
    {
        $this->passif_TEE[strtolower('M')]['rns'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeCfinale(float $mt)
    {
        $this->actif_TEE['M']['c'] += $mt;
        $this->getBilan_TEE();
    }

    public function TeeFbcf(float $mt)
    {
        $this->actif_TEE['E']['fbcf'] += $mt;
        $this->getBilan_TEE();
    }

    public function actif_TOF(string $agt, float $mt, int $sign)
    {
        $this->actif_TOF[$agt] += $sign * $mt;
        $this->getBilan_TOF();
    }

    public function passif_TOF(string $agt, float $mt, int $sign)
    {
        $this->passif_TOF[strtolower($agt)] += $sign * $mt;
        $this->getBilan_TOF();
    }
}