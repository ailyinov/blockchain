<?php

namespace App\Entity\Contracts;


use App\Entity\Account;

interface TransactionInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return Account
     */
    public function getAcceptor(): Account;

    /**
     * @param Account $account
     */
    public function countBalance(Account $account): void;

    /**
     * Return cloned accounts in order to perform dry operations without effects on real ones
     *
     * @return Account[]
     */
    public function getDetachedAccounts(): array;
}