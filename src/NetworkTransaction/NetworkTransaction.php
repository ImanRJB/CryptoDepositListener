<?php

namespace DepositListener\Src\NetworkTransaction;


class NetworkTransaction
{
    private $network;
    
    public function __construct($network)
    {
        $this->network = $network;
    }
    
    public function getOwnedTransactions($block)
    {
        $model = "DepositListener\\Src\\NetworkTransaction" . $this->network;
        return $model::getOwnedTransactions($block);
    }
}
