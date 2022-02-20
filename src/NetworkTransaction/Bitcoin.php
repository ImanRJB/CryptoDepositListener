<?php

namespace DepositListener\Src\NetworkTransaction;

use ImanRjb\BitcoinRpc\Services\BitcoinRpc\BitcoinRpc;

class Bitcoin
{
    public static function getTransactions($block)
    {
        $block_info = BitcoinRpc::getblockhash($block);
        $transactions = BitcoinRpc::getblock($block_info)['tx'];

        $all_transactions = new Transactions();
        foreach ($transactions as $transaction) {
            $transaction_detail = BitcoinRpc::getrawtransaction($transaction, true);

            foreach ($transaction_detail['vout'] as $tx) {

                if (isset($tx['scriptPubKey']['address']) and $tx['value'] > 0) {

                    $all_transactions->addTransaction(
                        $block,
                        $transaction_detail['txid'],
                        'btc',
                        $transaction_detail['txid'],
                        $tx['scriptPubKey']['address'],
                        $tx['value'],
                        $transaction_detail['confirmations']
                    );

                }
            }
        }

        return $all_transactions->getTransactions();
    }
}
