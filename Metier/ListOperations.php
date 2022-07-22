<?php
namespace Metier;
class ListOperations
{
    private array $_list;
    public function __construct(string ...$operation)
    {
        $this->_list = $operation;
    }
    public function add(string $operation): void
    {
        $this->_list[] = $operation;
    }
    public function all(): array
    {
        return $this->_list;
    }


}