<?php

namespace Alura\Leilao\Test\Models;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveAceitarLancesSeguidosDeUmUsuario()
    {
        $this->expectException(\DomainException::class);

        $joao = new Usuario("Joao");
        $ana = new Usuario("ana");

        $leilao = new Leilao("Leilao com 2 lances");
        $leilao->addLance(new Lance($joao, 2000));
        $leilao->addLance(new Lance($joao, 2500));
        $leilao->addLance(new Lance($ana, 1000));
        $leilao->addLance(new Lance($ana, 1000));
        $leilao->addLance(new Lance($ana, 1500));

        self::assertCount(2, $leilao->getLances());
    }

    #[DataProvider('geraLances')]
    public function testLeilaoDeveReceberLances($leilao, $qtdLances, $valores)
    {
        self::assertCount($qtdLances, $leilao->getLances());

        foreach($valores as $index => $valor) {
            self::assertEquals($valor, $leilao->getLances()[$index]->getValor());
        }
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);

        $joao = new Usuario("Joao");
        $maria = new Usuario("Maria");

        $leilao = new Leilao("Leilao com 2 lances");
        $leilao->addLance(new Lance($joao, 1000));
        $leilao->addLance(new Lance($maria, 2000));
        $leilao->addLance(new Lance($joao, 3000));
        $leilao->addLance(new Lance($maria, 4000));
        $leilao->addLance(new Lance($joao, 5000));
        $leilao->addLance(new Lance($maria, 6000));
        $leilao->addLance(new Lance($joao, 7000));
        $leilao->addLance(new Lance($maria, 8000));
        $leilao->addLance(new Lance($joao, 9000));
        $leilao->addLance(new Lance($maria, 10000));
        $leilao->addLance(new Lance($joao, 11000));
        $leilao->addLance(new Lance($maria, 12000));

        self::assertCount(10, $leilao->getLances());
        
        self::assertEquals(9000, $leilao->getLances()[array_key_last($leilao->getLances()) - 1]->getValor());
        self::assertEquals(10000, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public static function geraLances()
    {
        $joao = new Usuario("Joao");
        $maria = new Usuario("Maria");

        $leilao2Lances = new Leilao("Leilao com 2 lances");
        $leilao2Lances->addLance(new Lance($joao, 1000));
        $leilao2Lances->addLance(new Lance($maria, 2000));

        $leilao1Lance = new Leilao("Leilao com 1 lance");
        $leilao1Lance->addLance(new Lance($joao, 1000));

        return [
            "Leilao com 2 lances" => [$leilao2Lances, 2, [1000, 2000]],
            "Leilao com 1 lance" => [$leilao1Lance, 1, [1000]],
        ];
    }
}