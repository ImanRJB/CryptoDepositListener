<?php

namespace DepositListener\Src\NetworkTransaction;

class NetworkTransaction
{
    private $network;

    public function __construct($network)
    {
        $this->network = $network;
    }

    public function getBlockTransactions($block)
    {
        if (class_exists("DepositListener\\Src\\NetworkTransaction\\" . $this->network)) {
            $model = "DepositListener\\Src\\NetworkTransaction\\" . $this->network;
            return $model::getTransactions($block);
        }

        throw new \ErrorException('Network not found');
    }
}
