<?php
//declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HtmlAnchorReplacer\HtmlAnchorReplacer;

/*
Run the following command in the terminal to execute tests,
"./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/HtmlAnchorReplacerTest"
*/
final class HtmlAnchorReplacerTest extends TestCase
{
    public function testItReplacesOneHttpLinkInASentence()
    {
        $rawSentence = "This is a link, http://www.google.com in a string";
        $cleansedSentence = HtmlAnchorReplacer::replace($rawSentence);

        $this->assertEquals("This is a link, <a target='_blank' href='http://www.google.com'>http://www.google.com</a> in a string", $cleansedSentence);
    }

    public function testItAddsHttpPrefixIfMissing()
    {
        $rawSentence = "This is a link, www.google.com in a string";
        $cleansedSentence = HtmlAnchorReplacer::replace($rawSentence);

        $this->assertEquals("This is a link, <a target='_blank' href='http://www.google.com'>www.google.com</a> in a string", $cleansedSentence);
    }
}
