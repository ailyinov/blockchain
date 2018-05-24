<?php

namespace App\Entity;

class TransactionTransfer extends TransactionEmit
{
    /**
     * @var Account
     */
    private $sender;

    /**
     * TransactionTransfer constructor.
     * @param int $id
     * @param Account $acceptor
     * @param int $amount
     * @param Account $sender
     */
    public function __construct(int $id, Account $acceptor, int $amount, Account $sender)
    {
        $this->setSender($sender);

        parent::__construct($id, $acceptor, $amount);
    }

    /**
     * @return Account
     */
    public function getSender(): Account
    {
        return $this->sender;
    }

    /**
     * @param Account $sender
     */
    public function setSender(Account $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @param Account $acceptor
     */
    public function setAcceptor(Account $acceptor): void
    {
        if ($this->sender->getName() == $acceptor->getName()) {
            throw new \LogicException('Acceptor should be different from transmitter');
        }
        parent::setAcceptor($acceptor);
    }

    /**
     * @return array
     */
    protected function getSignatureFields(): array
    {
        return [$this->getId(), 1, $this->getSender()->getName(), $this->getAcceptor()->getName(), $this->getAmount()];
    }

    /**
     * @inheritdoc
     */
    public function countBalance(Account $account, int $balance): int
    {
        if ($this->getSender()->getName() == $account->getName()) {
            $balance -= $this->getAmount();
        }

        return parent::countBalance($account, $balance);
    }

    /**
     * @inheritdoc
     */
    public function getAccounts(): array
    {
        $account = $this->getSender();

        return array_merge([$account], parent::getAccounts());
    }
}