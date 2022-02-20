<?php

namespace DepositListener\Src\NetworkTransaction;

use ImanRjb\BitcoinRpc\Services\BitcoinRpc\BitcoinRpc;

class Bitcoin
{
    public function getBlockOwnedTransactions($block)
    {
        $block_info = BitcoinRpc::getblockhash($block);
        $transactions = BitcoinRpc::getblock($block_info)['tx'];

        $all_transactions = [];
        foreach ($transactions as $transaction) {
            $transaction_detail = BitcoinRpc::getrawtransaction($transaction, true);

            foreach ($transaction_detail['vout'] as $tx) {

                if (isset($tx['scriptPubKey']['address'])) {
                    $detail = [
                        'block' => $block,
                        'tx' => $transaction_detail['txid'],
                        'contract' => 'btc',
                        'from' => $transaction_detail['txid'],
                        'to' => $tx['scriptPubKey']['address'],
                        'value' => $tx['value'],
                        'confirmation' => $transaction_detail['confirmations'],
                    ];

                    if ($tx['value'] > 0) {
                        array_push($all_transactions, $detail);
                    }
                }
            }
        }

        return $all_transactions;
    }
}
