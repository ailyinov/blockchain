<?php

namespace App\Entity;

use App\Entity\Contracts\TransactionInterface;

class Block
{
    const TRANSACTIONS_LIMIT = 10;

    /**
     * @var int
     */
    private $id;

    /**
     * @var TransactionInterface[]
     */
    private $transactions = [];

    /**
     * Block constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return TransactionInterface[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param TransactionInterface $transaction
     */
    public function addTransaction(TransactionInterface $transaction)
    {
        if ($this->validateTransaction($transaction)) {
            $this->transactions[$transaction->getId()] = $transaction;
        }
    }

    /**
     * @param TransactionInterface $transaction
     * @return bool
     */
    private function validateTransaction(TransactionInterface $transaction): bool
    {
        return count($this->transactions) < self::TRANSACTIONS_LIMIT && !isset($this->transactions[$transaction->getId()]);
    }
}