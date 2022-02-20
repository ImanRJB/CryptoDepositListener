<?php

namespace DepositListener\BlockChain;

class NetworkTransaction
{
    private $network;

    public function __construct($network)
    {
        $this->network = $network;
    }

    public function getBlockTransactions($block)
    {
        if (class_exists("DepositListener\\BlockChain\\" . $this->network)) {
            $model = "DepositListener\\BlockChain\\" . $this->network;
            return $model::getBlockTransactions($block);
        }

        throw new \ErrorException('Network not found');
    }

    public function getTxConfirmationCount($txid)
    {
        if (class_exists("DepositListener\\BlockChain\\" . $this->network)) {
            $model = "DepositListener\\BlockChain\\" . $this->network;
            return $model::getTxConfirmationCount($txid);
        }

        throw new \ErrorException('Network not found');
    }
}