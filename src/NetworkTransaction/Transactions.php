<?php

namespace DepositListener\Src\NetworkTransaction;


class Transactions
{
    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    public function addTransaction($block, $tx, $contract, $from, $to, $value, $confirmation)
    {
        array_push($this->transactions, [
            'block' => $block,
            'tx' => $tx,
            'contract' => $contract,
            'from' => $from,
            'to' => $to,
            'value' => $value,
            'confirmation' => $confirmation,
        ]);
    }

    public function getTransactions()
    {
        return $this->transactions;
    }
}
