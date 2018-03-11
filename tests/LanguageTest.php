<?php

use PHPUnit\Framework\TestCase;

final class LanguageTest extends TestCase
{

    public function setUp()
    {
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . "/../";
        include_once $_SERVER['DOCUMENT_ROOT'] . 'includes/classes/Core.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . 'includes/classes/Languages.php';
    }

    public function tearDown()
    {
    }

    public function testLanguageLoader(): void
    {
        $locale = 'en_US';
        putenv("LC_MESSAGES=$locale");
        setlocale(LC_MESSAGES, $locale);
        bindtextdomain($locale, $_SERVER['DOCUMENT_ROOT'] . '/includes/locale');
        textdomain($locale);
        $this->assertEquals(
            'https://www.github.com/',
            gettext('github_link')
        );
    }
}