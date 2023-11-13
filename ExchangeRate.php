<?php declare(strict_types = 1);

require_once('./config.php');

/**
 * Třída pro načtení dat kurzového lístku a jejich vrácení jako HTML tabulky
 * 
 * @author: Anna Rývová
 * @copyright: Anna Rývová, 2023
 */
class ExchangeRate {
    /**
     * Načte soubor s daty kurzového lístku z url z adresy uložené v URL v config.php
     *
     * @return array{code: string, country: string, amount: float}
     */
    public function loadFile(): array
    {
        $content = file(URL);
        $exchangeRates = [];

        $i = 0;
        foreach ($content as $row) {
            // první dva řádky nezpracováváme, je to záhlaví
            if ($i < 2) {
                $i++;
                continue;
            }

            $parts = explode('|', $row);

            $exchangeRates[] = 
                [
                    'code' => $parts[3], 
                    'country' => $parts[0], 
                   'amount' => round((1000000 * (int) $parts[2]) / (float) preg_replace('/(\d+),(\d+)/i', '${1}.$2', $parts[4]), 3)
                ];

            $i++;
        }

        return $exchangeRates;
    }

    /**
     * Z dat $exchangeRates vytvoří HTML tabulku
     * (Připadá mi to dost jako prasárna, kodér ať kouká udělat nějakou LATTE/SMARTY apod. šablonu a do ní si vypíše hodnoty z pole - případně bych tu šablonu zvládla i já)
     *
     * @param array{code: string, country: string, amount: float} data kurzového lístku načtená metodou load()

     * @return string HTML tabulka obsahujcí data z $exchangeRates
     */
    public function exchangeRatesToTable(array $exchangeRates): string
    {
        $table = 
            '<table>' . PHP_EOL .
                '<thead>' .  PHP_EOL .              
                    '<tr><th>Kód měny</th><th>Země</th><th>Kurs 1 000 000 Kč</th></tr>' . PHP_EOL . 
                '</thead>' . PHP_EOL .
                '<tbody>' . PHP_EOL;

        foreach ($exchangeRates as $exchangeRate) {
            $table .= 
                // místo align="right" je lépe použít ext. CSS a nastavit class
                '<tr>' .
                    "<td>{$exchangeRate['code']}</td>" . 
                    "<td>{$exchangeRate['country']}</td>" . 
                    '<td align="right" nowrap>' . $this->addSpacesIntoNumber($exchangeRate['amount']) . '</td>' . 
                '</tr>' . PHP_EOL;         
        }

        $table .= 
                '</tbody>' . PHP_EOL .
            '</table>';

        return $table;
    }

    private function addSpacesIntoNumber(float $number): string {  
        // převeď na číslo a vrať zpět des. čárku místo tečky
        $parts = preg_split("/\./i", (string) $number);

        // desetinná část má vždy 3 místa (byla na ně zaokrouhlena, tzn. mezery přidávám jen do celočíselné části)
        $number = number_format((int) $parts[0], 0, ',', '&thinsp') . ',' . $parts[1]; 

        return $number;
    }
}

$exchangeRate = new ExchangeRate();
echo $exchangeRate->exchangeRatesToTable($exchangeRate->loadFile());