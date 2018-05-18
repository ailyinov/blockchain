<?php

namespace App\Entity;


use App\Entity\Contracts\Transaction\EmissionInterface;

class TransactionEmit implements EmissionInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Account
     */
    private $acceptor;

    /**
     * @var int
     */
    private $amount;

    /**
     * TransactionEmission constructor.
     * @param int $id
     * @param Account $acceptor
     * @param int $amount
     */
    public function __construct(int $id, Account $acceptor, int $amount)
    {
        $this->id = $id;
        $this->setAcceptor($acceptor);
        $this->setAmount($amount);
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
     * @return Account
     */
    public function getAcceptor(): Account
    {
        return $this->acceptor;
    }

    /**
     * @param Account $acceptor
     * @return $this
     */
    public function setAcceptor(Account $acceptor): void
    {
        $this->acceptor = $acceptor;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new \LogicException("Amount value should be greater than nothing");
        }
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    protected function getSignatureFields(): array
    {
        return [$this->getId(), 0, $this->getAcceptor()->getName(), $this->getAmount()];
    }

    /**
     * @return string
     */
    public function buildSignature()
    {
        $signString = join(':', $this->getSignatureFields());

        return md5($signString);
    }

    /**
     * @param int $balance
     * @param Account $account
     * @return int
     */
    public function countAccountBalance(Account $account): void
    {
        if ($this->getAcceptor()->getName() == $account->getName()) {
            $balance = $account->getBalance();
            $balance += $this->getAmount();
            $account->setBalance($balance);
        }
    }

    /**
     * @return array
     */
    public function getDetachedAccounts(): array
    {
        $account = clone $this->getAcceptor();

        return [$account];
    }
}