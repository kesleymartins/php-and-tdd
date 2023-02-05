<?php

declare(strict_types=1);

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private Lance $maiorLance;
    private Lance $menorLance;
    private array $maioresLances;

    public function avalia(Leilao $leilao): void
    {
        if ($leilao->estaFinalizado()) {
            throw new \DomainException("Nao e possivel avaliar leilao finalizado!");
        }

        if (empty($leilao->getLances())) {
            throw new \DomainException("Nao e possivel avaliar leilao vazio!");
        }

        $this->maiorLance = $leilao->getLances()[0];
        $this->menorLance = $leilao->getLances()[0];

        foreach($leilao->getLances() as $lance) {
            if ($lance->getvalor() > $this->maiorLance->getValor()) {
                $this->maiorLance = $lance;
            } 
            
            if ($lance->getvalor() < $this->menorLance->getValor()) {
                $this->menorLance = $lance;
            }
        }

        $lances = $leilao->getLances();
        usort($lances, function(Lance $lance1, Lance $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });
        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorLance(): Lance
    {
        return $this->maiorLance;
    }

    public function getMenorLance(): Lance
    {
        return $this->menorLance;
    }

    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}