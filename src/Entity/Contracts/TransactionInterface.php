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
     * @param Account $account
     * @return int
     */
    public function countBalance(Account $account, int $balance): int;

    /**
     * @return Account[]
     */
    public function getAccounts(): array;
}