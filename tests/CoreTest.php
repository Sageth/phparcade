<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPArcade\Core;

class CoreTest extends TestCase
{
    public function testGetCleanURLReplacesSpaces(): void
    {
        $this->assertSame('hello-world', Core::getCleanURL('hello world'));
    }

    public function testGetCleanURLRemovesTrailingDash(): void
    {
        $this->assertSame('hello', Core::getCleanURL('hello-'));
    }

    public function testGetCleanURLCollapsesDuplicateDashes(): void
    {
        $this->assertSame('hello-world', Core::getCleanURL('hello--world'));
    }

    public function testGetCleanURLReplacesNonWordChars(): void
    {
        $this->assertSame('hello-world', Core::getCleanURL('hello!world.'));
    }

    public function testShowGlyphReturnsSpanWithCorrectClasses(): void
    {
        $result = Core::showGlyph('check', '2x', 'true', 's');
        $this->assertSame("<span class='fas fa-check fa-2x' aria-hidden='true'></span>", $result);
    }

    public function testShowGlyphDefaultStyle(): void
    {
        $result = Core::showGlyph('ambulance');
        $this->assertStringContainsString('fa-ambulance', $result);
        $this->assertStringContainsString('fas', $result);
    }

    public function testDecodeHTMLEntityDecodesEntities(): void
    {
        $this->assertSame('<b>test</b>', Core::decodeHTMLEntity('&lt;b&gt;test&lt;/b&gt;', ENT_HTML5));
    }

    public function testDecodeHTMLEntityPassthroughsPlainText(): void
    {
        $this->assertSame('plain text', Core::decodeHTMLEntity('plain text'));
    }
}
