<?php

namespace DepositListener\Helper;

use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WalletHelper
{
    public function addDeposit(Wallet $wallet, $network, $transaction)
    {
        if (!$wallet->deposits()->whereTxid($transaction['tx'])->first()) {

            $wallet->deposits()->create([
                'type' => 'crypto',
                'network' => $network->name,
                'address' => $transaction['to'],
                'txid' => $transaction['tx'],
                'amount' => $transaction['value'],
                'received_at' => Carbon::now(),
                'deposit_confirmation' => $network->deposit_confirmation,
                'withdrawal_confirmation' => $network->withdrawal_confirmation,
                'confirmation_count' => 0,
            ]);
        }
    }

    public function addBalance(Wallet $wallet)
    {
        DB::beginTransaction();
        $min_deposit = $wallet->currency->min_deposit;
        $deposits = $wallet->deposits()
            ->where('deposited_at', null)
            ->where('confirmation_count', '>=', DB::raw('deposit_confirmation'))
            ->get();

        $sum = '0';
        foreach ($deposits as $deposit) {
            $sum = addAmount($sum, $deposit->amount);
        }

        if ($sum >= $min_deposit) {
            $wallet->update([
                'balance' => addAmount($wallet->balance, $sum)
            ]);

            foreach ($deposits as $deposit) {
                $deposit->update([
                    'deposited_at' => Carbon::now()
                ]);
            }
        }

        DB::commit();
    }

    public function confirmDeposit(Wallet $wallet)
    {
        DB::beginTransaction();

        $deposits = $wallet->deposits()
            ->where('confirmed_at', null)
            ->where('confirmation_count', '>=', DB::raw('withdrawal_confirmation'))
            ->get();

        foreach ($deposits as $deposit) {
            $deposit->update([
                'confirmed_at' => $deposit->confirmation_count >= $deposit->withdrawal_confirmation ? Carbon::now() : null
            ]);
        }

        DB::commit();
    }

    public function updateDeposits(Wallet $wallet)
    {
        $this->addBalance($wallet);
        $this->confirmDeposit($wallet);
    }
}