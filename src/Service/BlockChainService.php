<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Block;
use App\Entity\BlockChain;
use App\Entity\TransactionEmit;
use App\Entity\TransactionTransfer;

class BlockChainService
{
    /**
     * @var BlockChain
     */
    private $blockChain;

    /**
     * BlockChainService constructor.
     * @param BlockChain $blockChain
     */
    public function __construct(BlockChain $blockChain)
    {
        $this->blockChain = $blockChain;
    }

    /**
     * @param Account $account
     */
    public function emit(Account $account, int $trId, int $blockId, ?int $parentBlock, int $amount): void
    {
        $transaction = new TransactionEmit($trId, $account, $amount);
        $block = new Block($blockId);
        $block->addTransaction($transaction);
        $this->blockChain->addBlock($parentBlock, $block);
    }

    /**
     * @param Account $sender
     * @param Account $acceptor
     */
    public function transfer(Account $sender, Account $acceptor, int $trId, int $blockId, ?int $parentBlock, int $amount): void
    {
        $transaction = new TransactionTransfer($trId, $acceptor, $amount, $sender);
        $block = new Block($blockId);
        $block->addTransaction($transaction);
        $this->blockChain->addBlock($parentBlock, $block);
    }

    /**
     * @param Account $account
     * @return int
     */
    public function getBalance(Account $account): int
    {
        return $this->blockChain->getBalance($account);
    }
}