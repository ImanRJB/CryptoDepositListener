<?php

namespace DepositListener\Src;

use Illuminate\Support\ServiceProvider;

class DepositListenerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // For load config files
        if (file_exists(__DIR__ . '/../src/config/hd-wallet.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../src/config/hd-wallet.php', 'hd-wallet');
        }

//        $this->app->alias(\HdWallet\Src\Services\AddressGenerator\AddressGenerator::class, 'AddressGenerator');
//        $this->app->alias(\HdWallet\Src\Services\PrivateKeyGenerator\PrivateKeyGenerator::class, 'PrivateKeyGenerator');

        $this->app->register(\Mylesdc\LaravelEthereum\EthereumServiceProvider::class);
    }
}
