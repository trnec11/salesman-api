<?php
declare(strict_types=1);

namespace App\Helpers;

class Diacritics
{
    /**
     * @param string $text
     * @return string
     */
    public static function replaceDiacritics(string $text): string
    {
        $diacritics = array('ľ','š','č','ť','ž','ý','á','í','é','Č','Á','Ž','Ý');
        $cor = array('l','s','c','t','z','y','a' ,'i' ,'e', 'C' ,'A', 'Z', 'Y');

        return str_replace($diacritics, $cor, $text);
    }
}
