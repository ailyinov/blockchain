<?php

namespace App\Entity\Contracts\Transaction;

use App\Entity\Account;

interface TransferInterface extends EmissionInterface
{
    public function getSender(): Account;
}