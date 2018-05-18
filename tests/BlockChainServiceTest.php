<?php

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BlockChainServiceTest extends TestCase
{
    public function testBalance()
    {
        $blockChain = new \App\Entity\BlockChain();
        $blockChainService = new \App\Service\BlockChainService($blockChain);

        $accAlice = new \App\Entity\Account('Alice');
        $blockChainService->emit($accAlice, 0, 0, null, 100);

        $accBob = new \App\Entity\Account('Bob');

        // good operations
        $blockChainService->transfer($accAlice, $accBob, 0, 1, 0, 10);
        $blockChainService->transfer($accBob, $accAlice,0, 2, 1, 3);
        $blockChainService->transfer($accAlice, $accBob,0, 3, 2, 30);

        // not enough coins
        $blockChainService->transfer($accBob, $accAlice,0, 5, 3, 300);

        // small tree branch
        $blockChainService->transfer($accAlice, $accBob,0, 4, 0, 34);

        // Missed parent block
        $blockChainService->transfer($accAlice, $accBob,0, 4, 15, 34);

        $accDan = new \App\Entity\Account('Dan');
        $blockChainService->emit($accDan, 1, 6, 3, 15);

        $blockChainService->transfer($accAlice, $accDan, 2, 7, 6, 8);

        $balanceAlice = $blockChainService->getBalance($accAlice);
        $balanceBob = $blockChainService->getBalance($accBob);
        $balanceDan = $blockChainService->getBalance($accDan);

        $this->assertEquals(55, $balanceAlice);
        $this->assertEquals(37, $balanceBob);
        $this->assertEquals(23, $balanceDan);
    }
}