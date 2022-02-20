<?php

namespace DepositListener\BlockChain;

use ImanRjb\BitcoinRpc\Services\BitcoinRpc\BitcoinRpc;

class Bitcoin
{
    public static function getBlockTransactions($block)
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
                        'BTC',
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

    public static function getTxConfirmationCount($txid)
    {
        $transaction = BitcoinRpc::getrawtransaction($txid, true);
        
        return [
            'count' => isset($transaction['confirmations']) ? $transaction['confirmations'] : 0,
            'success' => isset($transaction['confirmations']) ? true : false
        ];
    }
}
