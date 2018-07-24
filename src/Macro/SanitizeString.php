<?php
namespace SpedTransform\Macro;

trait SanitizeString
{
    public function toUpper($string)
    {
        return mb_strtoupper($string);
    }

    public function toLower($string)
    {
        return mb_strtolower($string);
    }

    /**
     * Replace all specials characters from string and retuns only 128 basics
     * NOTE: only for UTF-8
     * @param string $string
     * @return  string
     */
    public function replaceSpecialsChars($string)
    {
        $string = trim($string);
        $aFind = ['&','á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü',
            'ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç'];
        $aSubs = ['e','a','a','a','a','e','e','i','o','o','o','u','u',
            'c','A','A','A','A','E','E','I','O','O','O','U','U','C'];
        $newstr = str_replace($aFind, $aSubs, $string);
        $newstr = preg_replace("/[^a-zA-Z0-9 @,-_.;:\/]/", "", $newstr);
        return $newstr;
    }

    /**
     * Remove all non numeric characters from string
     * @param string $string
     * @return string
     */
    public function onlyNumbers($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }
}
