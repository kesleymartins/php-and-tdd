<?php

declare(strict_types=1);

namespace Alura\Leilao\Test\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        
        $leilao = new Leilao("Vazio");
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $ana = new Usuario("ana");

        $leilao = new Leilao("Leilao finalizado");
        $leilao->addLance(new Lance($ana, 1000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    #[DataProvider('leilaoProvider')]
    public function testAvaliadorDeveEncotrarMaiorValor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        self::assertEquals(4000, $this->leiloeiro->getMaiorLance()->getValor());
    }

    #[DataProvider('leilaoProvider')]
    public function testAvaliadorDeveEncotrarMenorValor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        self::assertEquals(1000, $this->leiloeiro->getMenorLance()->getValor());
    }

    #[DataProvider('leilaoProvider')]
    public function testAvaliadorBuscaTresMaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        self::assertCount(3, $this->leiloeiro->getMaioresLances());

        foreach([4000, 2500, 2000] as $index => $valor) {
            self::assertEquals($valor, $this->leiloeiro->getMaioresLances()[$index]->getValor());
        }
    }

    public static function leilaoProvider(): array
    {
        $leilaoCrescente = new Leilao("Crescente");
        $leilaoDecrescente = new Leilao("Decrescente");
        $leilaoAleatorio = new Leilao("Aleatorio");

        $maria = new Usuario("Maria");
        $joao = new Usuario("Joao");
        $ana = new Usuario("ana");

        $leilaoCrescente->addLance(new Lance($maria, 1000));
        $leilaoCrescente->addLance(new Lance($joao, 2000));
        $leilaoCrescente->addLance(new Lance($ana, 2500));
        $leilaoCrescente->addLance(new Lance($maria, 4000));

        $leilaoDecrescente->addLance(new Lance($ana, 4000));
        $leilaoDecrescente->addLance(new Lance($joao, 2500));
        $leilaoDecrescente->addLance(new Lance($maria, 2000));
        $leilaoDecrescente->addLance(new Lance($joao, 1000));

        $leilaoAleatorio->addLance(new Lance($joao, 2500));
        $leilaoAleatorio->addLance(new Lance($ana, 1000));
        $leilaoAleatorio->addLance(new Lance($joao, 4000));
        $leilaoAleatorio->addLance(new Lance($maria, 2000));

        return [
            "Lances em ordem crescente" => [$leilaoCrescente],
            "Lances em ordem decrescente" => [$leilaoDecrescente],
            "Lances em ordem aleatoria" => [$leilaoAleatorio]
        ];
    }
}