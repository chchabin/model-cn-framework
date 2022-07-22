<?php
namespace Metier;
class ListAgents
{
    private array $_list;
    public function __construct()
    {
        $this->_list = [];
    }
    public function add( $agent): void
    {
        $this->_list[] = $agent;
    }
    public function all(): array
    {
        return $this->_list;
    }
    public function find(string $nom){
        (object) $recherche =null;
        foreach ($this->all() as $unAgent){
            if ($unAgent->getNom()==$nom){
                $recherche=$unAgent;
            }
        }
        return $recherche;
    }
    public function raz():void{
        $this->_list = [];
    }
}