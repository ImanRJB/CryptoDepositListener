<?php

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (! function_exists('addAmount') ) {
    function addAmount(string $num1, string $num2, ?int $scale = 18)
    {
        $bcadd = bcadd($num1, $num2, $scale);
        $rtrim = rtrim($bcadd, '0');
        return rtrim($rtrim, '.');
    }
}

if (! function_exists('subAmount') ) {
    function subAmount(string $num1, string $num2, ?int $scale = 18)
    {
        $bcsub = bcsub($num1, $num2, $scale);
        $rtrim = rtrim($bcsub, '0');
        return rtrim($rtrim, '.');
    }
}

if (! function_exists('mulAmount') ) {
    function mulAmount(string $num1, string $num2, ?int $scale = 18)
    {
        $bcmul = bcmul($num1, $num2, $scale);
        $rtrim = rtrim($bcmul, '0');
        return rtrim($rtrim, '.');
    }
}

if (! function_exists('divAmount') ) {
    function divAmount(string $num1, string $num2, ?int $scale = 18)
    {
        $bcdiv = bcdiv($num1, $num2, $scale);
        $rtrim = rtrim($bcdiv, '0');
        return rtrim($rtrim, '.');
    }
}
