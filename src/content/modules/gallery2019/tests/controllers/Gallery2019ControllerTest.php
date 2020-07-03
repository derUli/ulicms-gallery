<?php

use Gallery2019\Gallery;
use Spatie\Snapshots\MatchesSnapshots;

class Gallery2019ControllerTest extends \PHPUnit\Framework\TestCase
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        include_once getLanguageFilePath("en");
        Translation::loadAllModuleLanguageFiles("en");
        $manager = new UserManager();
        $users = $manager->getAllUsers();
        $firstUser = $users[0];

        $_SESSION = [
            "login_id" => $firstUser->getId()
        ];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        Database::query("delete from `{prefix}gallery` where title like 'Test - %'", true);
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

        $gallery1 = new Gallery();
        $gallery1->setTitle("Test - 1");
        $gallery1->save();
        $code1 = "[gallery={$gallery1->getId()}]";

        $gallery2 = new Gallery();
        $gallery2->setTitle("Test - 2");
        $gallery2->save();
        $code2 = "[gallery={$gallery2->getId()}]";

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
