<?php

declare(strict_types=1);

namespace Alura\Leilao\Model;

class Lance
{
    private Usuario $usuario;
    private float $valor;

    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }
}
