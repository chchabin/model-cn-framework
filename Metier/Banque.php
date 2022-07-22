<?php
namespace Metier;


class Banque
{
    private string $_achtr;
    private string $_colonneT = 'B';
    private array $actif = [
        'BC' => 0,
        'M' => 0,
        'Bfi' => 0,
        'Rdm' => 0
    ];
    private array $passif = [
        'BC' => 0,
        'M' => 0,
        'Bfi' => 0,
        'Rdm' => 0
    ];
    private array $bilan;

    public function __construct(string $achtr)
    {
        $this->_achtr = $achtr;

    }
    public function getNom(): string
    {
        return $this->_achtr;
    }
    /**
     * @return int
     */
    public function getColonneT(): int
    {
        return $this->_colonneT;
    }

    public function getBilan()
    {
        $i = 0;
        foreach ($this->actif as $k => $v) {
            $this->bilan[$i] = [$k => $v];
            $i++;
        }

        $i = 0;
        foreach ($this->passif as $k => $v) {
            $this->bilan[$i] += [strtolower($k) => $v];
            $i++;
        }

        return $this->bilan;
    }

    public function BanqueFin_Actif(string $ent, float $montant): void
    {
        if (!isset($this->actif[$ent])) {
            $this->actif[$ent] = 0;
        }
        if (!isset($this->passif[$ent])) {
            $this->passif[$ent] = 0;
        }
        $this->actif[$ent] += $montant;
        $this->getBilan();
    }

    public function BanqueFin_Passif(string $ent, float $montant)
    {
        if (!isset($this->passif[$ent])) {
            $this->passif[$ent] = 0;
        }
        if (!isset($this->actif[$ent])) {
            $this->actif[$ent] = 0;
        }
        $this->passif[$ent] += $montant;
        $this->getBilan();

    }


}