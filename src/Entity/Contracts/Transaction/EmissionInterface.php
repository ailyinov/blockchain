<?php

namespace App\Entity\Contracts\Transaction;


use App\Entity\Account;

interface EmissionInterface
{
    public function getId(): int;

    public function getAcceptor(): Account;

    public function countAccountBalance(Account $account): void;

    public function getDetachedAccounts(): array;
}