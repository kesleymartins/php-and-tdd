<?php

declare(strict_types=1);

namespace Alura\Leilao\Model;

class Usuario
{
    private string $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }
}