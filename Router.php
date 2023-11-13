<?php declare(strict_types = 1);

require_once (__DIR__ . '/config.php');

/**
 * Class Router 
 * Třída pro redirect podle "hezkého" url
 * 
 * @author Anna Rývová
 * @copyright Anna Rývová, 2023
 * 
 * Tohle jsem taky nikdy neřešila. Router jsme používali buď Neťácký v Nette aplikacích anebo tam, kde jsme jeli čisté
 * PHPko bez Nette, tak jsme měli šikulku admina, který to nastavil v .htaccess, což je podle mne mnohem lepší a čistčí způsob
 * než to motat do PHPka
 */
class Router {
    /**
     * Přeloží "hezkou" url 
     *
     * @param string $url
     * @return void
     */
    public function route(string $url): void {
        if ($url === '/') {
            // return PAGES_LIST[$url];
            header('Location: http://localhost');
        }

        $isList = true;

        // odstraň lomítko na konci $url pokud tam je
        $url = preg_replace("/(\/$)/", "", $url);

        // pokud url končí na číslo, jedná se o detail produktu
        $endsWithNumber = preg_match("/.*?(\d+)$/", $url, $matches);
        if ($endsWithNumber > 0) {
            // je to detail - z url odstraň poslední část s názvem produktu
            $url = preg_replace("/^(\/.*)*(\/(.)*)$/", '$1', $url, 1, $count);
            $isList = false;
        }

        // pokud není v $pagesList (viz config.php), tak přesměruj na chybovou stránku 404.php
        if (!in_array($url, array_keys(PAGES_LIST))) {
            $this->notFound('404.php');
            die;
        } 

        if ($isList === true) {
           // include(__DIR__ . PAGES_LIST[$url]);
           // return PAGES_LIST[$url] . '/list.php';
           header("Location: http://localhost" . PAGES_LIST[$url] . '/list.php'); 
           die;
        }
        else {
            // return PAGES_LIST[$url] . '/detail.php';
            header("Location: http://localhost" . PAGES_LIST[$url] . '/detail.php');
            die;
        }
    }

    /**
     * Přesměruje na ERROR 404 pro neplatnou url
     *
     * @param string $file
     * 
     * @return void
     */
    public function notFound(string $file): void {
        include($file);

        die;
    }
}

$urls = 
    [
        '/', 
        '/produkty',
        '/produkty/elektronika',
        '/produkty/elektronika/',
        '/produkty/elektronika/tv-samsung-422', 
        '/produkty/elektronika/tv-samsung-422/',
        '/produkty/boty', 
        '/produkty/boty/adidas-terrex-ax3', 
        '/produkty/potraviny'
    ];     

// pro test odkomentuj příslušný řádek    
$url = '/';
// $url = '/produkty';
// $url = '/produkty/elektronika';
// $url = '/produkty/elektronika/';
// $url = '/produkty/elektronika/tv-samsung-422';
// $url = '/produkty/elektronika/tv-samsung-422/';
// $url = '/produkty/boty';
// $url = '/produkty/boty/adidas-terrex-ax3';
// $url = '/produkty/potraviny';

$router = new Router();
$router->route($url);

