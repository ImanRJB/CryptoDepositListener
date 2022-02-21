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

    public function getTransaction($txid)
    {
        if (class_exists("DepositListener\\BlockChain\\" . $this->network)) {
            $model = "DepositListener\\BlockChain\\" . $this->network;
            return $model::getTransaction($txid);
        }

        throw new \ErrorException('Network not found');
    }

    public function valueCalculator($value, $decimal)
    {
        if (class_exists("DepositListener\\BlockChain\\" . $this->network)) {
            $model = "DepositListener\\BlockChain\\" . $this->network;
            return $model::valueCalculator($value, $decimal);
        }

        throw new \ErrorException('Network not found');
    }
}
