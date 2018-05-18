<?php

namespace App\Entity;

use App\Entity\Contracts\Transaction\EmissionInterface;

class Block
{
    const TRANSACTIONS_LIMIT = 10;

    /**
     * @var int
     */
    private $id;

    /**
     * @var EmissionInterface[]
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
     * @return EmissionInterface[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param EmissionInterface $transaction
     */
    public function addTransaction(EmissionInterface $transaction)
    {
        if ($this->validateTransaction($transaction)) {
            $this->transactions[$transaction->getId()] = $transaction;
        }
    }

    /**
     * @param EmissionInterface $transaction
     * @return bool
     */
    private function validateTransaction(EmissionInterface $transaction): bool
    {
        if (count($this->transactions) > self::TRANSACTIONS_LIMIT) {
            return false;
        }
        if (isset($this->transactions[$transaction->getId()])) {
            return false;
        }

        return true;
    }
}