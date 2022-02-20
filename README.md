# Cryptocurrency deposit listener for multiple cryptocurrencies


#### Put below parameters in <code>.env</code> file

```dotenv
BITCOIND_HOST=
BITCOIND_PORT=
BITCOIND_USER=
BITCOIND_PASSWORD=

ETH_HOST=
ETH_PORT=

BSC_HOST=
BSC_PORT=

```

#### How to use:

```php
$network = new NetworkTransaction('Binance');
return $network->getTransactions(16917593);
```

#### Supported networks:

Network | Base Currency
---------------------|--------
Bitcoin              |BTC
Ethereum             |ETH
Binance              |BNB
Tron                 |TRX