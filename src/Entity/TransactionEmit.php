<?php

namespace App\Entity;


use App\Entity\Contracts\TransactionInterface;

class TransactionEmit implements TransactionInterface
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
     * @inheritdoc
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
     * @inheritdoc
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
            throw new \LogicException("Amount value should be positive");
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
     * @inheritdoc
     */
    public function countBalance(Account $account, int $balance): int
    {
        if ($this->getAcceptor()->getName() == $account->getName()) {
            $balance += $this->getAmount();
        }

        return $balance;
    }

    /**
     * @inheritdoc
     */
    public function getAccounts(): array
    {
        $account = $this->getAcceptor();

        return [$account];
    }
}