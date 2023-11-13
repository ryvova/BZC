<?php declare(strict_types = 1);

/**
 * Automaticky načte třídy z adresáře
 * 
 * @author: Anna Rývová
 * @copyright: Anna Rývová, 2023
 * 
 * Tohle se přiznám, že jsem nikdy neřešila - vždy to řešil nějaký nejvyšší šéf jednou pro celý projekt.
 * Když už bych to mermomocí chtěla psát v PHP někde, kde není kompletní Nette, tak bych si tam asi doinstalovala neťácký RobotLoader - viz
 * https://php.baraja.cz/autoloading-trid
 * 
 * @return void
 */
function autoload(string $dir): void
{
    spl_autoload_register(function (string $className) use ($dir): void {
        include $dir. DIRECTORY_SEPARATOR . "{$className}.php";
    });
}

autoload(__DIR__);

$exchangeRate = new ExchangeRate();
echo $exchangeRate->exchangeRatesToTable($exchangeRate->loadFile());
