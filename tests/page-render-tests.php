<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class page_render_tests extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        resource_resolver::init(
            __DIR__ . "/resources/content",
            realpath(__DIR__ . "/resources")
        );
        page_render::$settings_file = __DIR__ . "/resources/site.xml";
    }


    public function candidatePage1()
    {
        $xml = "";
        $xml .= "<pagedef loc='sub1home' default='1' template='main' title='Main Page' description='a new page' keywords='a,b,c'>\n";
        $xml .= "  <content id='content' src='main-content.html' />\n";
        $xml .= "</pagedef>";

        return new xml_file($xml);
    }

    public function testDefaultLookup(): void
    {
        $candidate = $this->candidatePage1();
        $result = page_render::make_page($candidate);

        print "\n---------------------------------\n";
        print(xml_file::make_tidy_string($result->saveXML(), "xml"));
        print "\n---------------------------------\n";

        $this->assertTrue(true);
    }
}
