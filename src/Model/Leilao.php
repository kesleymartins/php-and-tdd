<?php

declare(strict_types=1);

namespace Alura\Leilao\Model;

class Leilao
{
    private array $lances;
    private string $descricao;
    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function addLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            throw new \DomainException("Usuario nao pode realizar 2 lances consecutivos.");
        }

        if ($this->getTotalLancesUsuario($lance->getUsuario()) >= 5) {
            throw new \DomainException("Usuario nao pode realizar mais de 5 lances.");
        }

        $this->lances[] = $lance;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function finaliza(): void
    {
        $this->finalizado = true;
    }

    public function estaFinalizado()
    {
        return $this->finalizado;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function getTotalLancesUsuario(Usuario $usuario) 
    {
        return array_reduce(
            $this->lances, 
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }

                return $totalAcumulado;
            }, 0
        );
    }
}
