<?php


namespace Alura\Leilao\Tests\Integracao\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{

    private static \PDO $con;

    public static function setUpBeforeclass(): void
    {
        self:: $con = new \PDO('sqlite::memory');
        self:: $con->exec('
            create table leiloes
            (
                id INTEGER
                    primary key,
                descricao TEXT,
                finalizado BOOL,
                dataInicio TEXT
            );');
    }

    protected function setUp(): void
    {
        self:: $con->beginTransaction();
    }

    /**
     * @dataProvider  leiloes
     */
    public function testBucaLeiloesNaoFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self:: $con);

        foreach ($leiloes as $leilao){
            $leilaoDao->salva($leilao);

        }

        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1,$leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class,$leiloes);
        self::assertSame(
            'Variante 0km',
            $leiloes[0]->recuperarDescricao()
        );
        self::assertFalse($leiloes[0]->estaFinalizado());

    }

    /**
     * @dataProvider  leiloes
     */
    public function testBucaLeiloesFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self:: $con);

        foreach ($leiloes as $leilao){
            $leilaoDao->salva($leilao);

        }

        $leiloes = $leilaoDao->recuperarFinalizados();

        self::assertCount(1,$leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class,$leiloes);
        self::assertSame(
            'Fiat 147 0Km',
            $leiloes[0]->recuperarDescricao()
        );
        self::assertTrue($leiloes[0]->estaFinalizado());
    }

    protected function tearDown(): void
    {
        self:: $con->rollBack();
    }

    public function Leiloes()
    {
        $naoFinalizado = new Leilao('Variante 0km');
        $finalizado = new Leilao('Fiat 147 0Km');
        $finalizado->finaliza();

        return [
            [
                [$naoFinalizado,$finalizado]
            ]
        ];
    }

}