<?php

use Spatie\Snapshots\MatchesSnapshots;

class Gallery2019ControllerTest extends \PHPUnit\Framework\TestCase
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        Translation::loadAllModuleLanguageFiles("en");
    }

    public function testGetSettingsLinkText()
    {
        $controller = new Gallery2019Controller();
        $this->assertMatchesTextSnapshot($controller->getSettingsLinkText());
    }

    public function testGetSettingsHeadline()
    {
        $controller = new Gallery2019Controller();
        $this->assertMatchesTextSnapshot($controller->getSettingsHeadline());
    }

    public function testSettings()
    {
        $controller = new Gallery2019Controller();
        $this->assertMatchesTextSnapshot($controller->settings());
    }

    public function testContentFilterReturnsString()
    {
        $controller = new Gallery2019Controller();

        $code1 = "[gallery=" . PHP_INT_MAX . "]";
        $code2 = "[gallery=" . (PHP_INT_MAX - 2) . "]";
        $input = "<div>{$code1}Foo{$code2}</div>";
        $this->assertMatchesHtmlSnapshot($controller->contentFilter($input));
    }

    public function testContentFilterReturnsOriginalString()
    {
        $controller = new Gallery2019Controller();
        $input = "<div>Foo</div>";
        $this->assertEquals($input, $controller->contentFilter($input));
    }
}
