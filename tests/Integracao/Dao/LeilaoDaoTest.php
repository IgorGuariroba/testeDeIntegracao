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
        $leilao = new Leilao('Variante 0km');
        $con = ConnectionCreator::getConnection();
        $leilaoDao = new LeilaoDao($con);

        $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1,$leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class,$leiloes);
        self::assertSame(
            'Variante 0km',
            $leiloes[0]->recuperarDescricao()
        );

        $con->exec('DELETE FROM leiloes WHERE true;');
    }

}