<?php

namespace DepositListener\BlockChain;


use BCMathExtended\BC;

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

    public function bcDecodeValue($hex, $decimal = 18)
    {
        $num = $this->bchexdec($hex);
        $num = str_pad($num, $decimal, 0, STR_PAD_LEFT);

        $dec = substr($num, -$decimal);

        $int = substr($num, 0, -$decimal);

        $final = $int . '.' . $dec;

        $final = rtrim($final, "0");
        $final = rtrim($final, ".");

        if (substr($final, 0, 1) === '.') {
            return '0' . $final;
        }

        return $final;
    }

    private function bchexdec($hex)
    {
        $remainingDigits = substr($hex, 0, -1);
        $lastDigitToDecimal = \hexdec(substr($hex, -1));

        if (strlen($remainingDigits) === 0) {
            return $lastDigitToDecimal;
        }

        return BC::add(BC::mul(16, $this->bchexdec($remainingDigits)), $lastDigitToDecimal, 0);
    }
}
