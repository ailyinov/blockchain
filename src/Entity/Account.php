<?php

namespace App\Entity;


class Account
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $balance = 0;

    /**
     * User constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): void
    {
        $len = strlen($name);
        if ($len < 2 || $len > 10) {
            throw new \LogicException('Account name should be longest than 2 and shorted than 10 characters');
        }
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     * @return $this
     */
    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }
}