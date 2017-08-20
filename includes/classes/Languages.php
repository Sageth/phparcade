<?php
declare(strict_types = 1);
Core::stopDirectAccess();

class Languages
{
    private function __construct()
    {
    }
    public static function loadLanguage()
    {
        $locale = 'en_US';
        putenv("LC_ALL=$locale");
        setlocale(LC_ALL, $locale);
        bindtextdomain($locale, $_SERVER['DOCUMENT_ROOT'] . '/includes/locale');
        textdomain($locale);
    }
    private function __clone()
    {
    }
}
