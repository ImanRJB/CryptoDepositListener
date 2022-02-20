<?php

namespace DepositListener\BlockChain;

use BCMathExtended\BC;
use Mylesdc\LaravelEthereum\Facade\Ethereum as EthereumService;
use Mylesdc\LaravelEthereum\Lib\JsonRPC;

class Ethereum implements BlockChainInterface
{
    public static function getBlockTransactions($block)
    {
        $node = new JsonRPC(config('ethereum.host'), config('ethereum.port'));
        $block = $node->request('eth_getBlockByNumber', ['0x' . dechex($block), true])->result;
        $transactions = json_decode(json_encode($block->transactions), true);

        $last_block = $node->request('eth_blockNumber')->result;
        $last_block = hexdec($last_block);

        $all_transactions = new Transactions();
        foreach ($transactions as $transaction) {

            // ETH
            if ($transaction['input'] == '0x') {

                $all_transactions->addTransaction(
                    hexdec($transaction['blockNumber']),
                    $transaction['hash'],
                    'ETH',
                    $transaction['from'],
                    $transaction['to'],
                    substr($transaction['value'], 2),
                    $last_block - hexdec($transaction['blockNumber']),
                );

            }
            // ERC20 Tokens
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

    public static function getTxConfirmationCount($txid)
    {
        $node = new JsonRPC(config('ethereum.host'), config('ethereum.port'));
        $last_block = $node->request('eth_blockNumber')->result;
        $transaction = $node->request('eth_getTransactionByHash', [$txid])->result;
        $transaction = json_decode(json_encode($transaction), true);

        return [
            'count' => hexdec($last_block) -  hexdec($transaction['blockNumber']),
            'success' => isset($transaction['maxFeePerGas']) ? true : false
        ];
    }
    
    public static function valueCalculator($value, $decimal)
    {
        return self::bcDecodeValue($value, $decimal);
    }

    private function bcDecodeValue($hex, $decimal = 18)
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
