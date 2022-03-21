<?php


namespace Alura\Leilao\Tests\Integracao\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    public function testInsecaoEBuscaDevemFuncionar()
    {
        $leilao = new Leilao('Variante )km');
        $leilaoDao = new LeilaoDao(ConnectionCreator::getConnection());

        $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1,$leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class,$leiloes);
        self::assertSame(
            'Variante 0Km',
            $leiloes[0]->recuperarDescricao()
        );
    }

}