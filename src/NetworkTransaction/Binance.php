<?php

namespace DepositListener\Src\NetworkTransaction;

use Mylesdc\LaravelEthereum\Facade\Ethereum as EthereumService;
use Mylesdc\LaravelEthereum\Lib\JsonRPC;

class Binance
{
    public static function getTransactions($block)
    {
        $node = new JsonRPC(config('binance.host'), config('binance.port'));
        $block = $node->request('eth_getBlockByNumber', ['0x' . dechex($block), true])->result;
        $transactions = json_decode(json_encode($block->transactions), true);

        $last_block = $node->request('eth_blockNumber')->result;
        $last_block = hexdec($last_block);

        $all_transactions = new Transactions();
        foreach ($transactions as $transaction) {

            // BNB
            if ($transaction['input'] == '0x') {

                $all_transactions->addTransaction(
                    hexdec($transaction['blockNumber']),
                    $transaction['hash'],
                    'bnb',
                    $transaction['from'],
                    $transaction['to'],
                    substr($transaction['value'], 2),
                    $last_block - hexdec($transaction['blockNumber']),
                );

            }
            // BEP20 Tokens
            else {
                if (substr($transaction['input'], 0, 10) == '0xa9059cbb') {

                    $all_transactions->addTransaction(
                        hexdec($transaction['blockNumber']),
                        $transaction['hash'],
                        $transaction['to'],
                        $transaction['from'],
                        '0x' . substr($transaction['input'], 34, 40),
                        substr($transaction['input'], 74),
                        $last_block - hexdec($transaction['blockNumber']),
                    );

                }
            }
        }

        return $all_transactions->getTransactions();
    }
}
