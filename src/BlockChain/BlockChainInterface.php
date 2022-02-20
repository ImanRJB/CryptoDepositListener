<?php

namespace DepositListener\BlockChain;

interface BlockChainInterface {
    public static function getBlockTransactions($block);
    public static function getTxConfirmationCount($txid);
    public static function valueCalculator($value, $decimal);
}
