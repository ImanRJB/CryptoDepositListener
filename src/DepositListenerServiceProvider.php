<?php

namespace DepositListener\Src;

use Illuminate\Support\ServiceProvider;

class DepositListenerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // For load config files
        if (file_exists(__DIR__ . '/../src/config/bitcoind.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../src/config/bitcoind.php', 'bitcoind');
        }
        if (file_exists(__DIR__ . '/../src/config/ethereum.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../src/config/ethereum.php', 'ethereum');
        }

        $this->app->register(\Mylesdc\LaravelEthereum\EthereumServiceProvider::class);
    }
}
