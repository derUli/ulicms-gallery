<?php

include_once dirname(__FILE__) . "/../Gallery2019BaseTest.php";

use Gallery2019\Gallery;

class GalleryImageControllerTest extends Gallery2019BaseTest
{
    private $gallery = null;

    protected function setUp(): void
    {
        parent::setUp();
        $manager = new UserManager();
        $users = $manager->getAllUsers();
        $firstUser = $users[0];

        $_SESSION = [
            "login_id" => $firstUser->getId()
        ];

        $controller = new GalleryController();
        $_POST["title"] = "Test - " . time();

        $id = $controller->_createPost();
        $_POST["gallery_id"] = $id;
        $this->gallery = new Gallery($id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
        $_SESSION = [];
    }

    public function testCreatePost()
    {
        $controller = new GalleryImageController();
        $_POST["position"] = "1";
        $_POST["path"] = "foo.jpg";
        $_POST["description"] = "Hello World";
        $this->assertGreaterThanOrEqual(1, $controller->_createPost());
    }

    public function testEditPost()
    {
        $controller = new GalleryImageController();
        $_POST["position"] = "1";
        $_POST["path"] = "foo.jpg";
        $_POST["description"] = "Hello World";
        $controller->_createPost();

        $_POST["position"] = "20";
        $_POST["path"] = "test 123.jpg";
        $_POST["description"] = "Hello World 123";
        $updatedModel = $controller->_editPost();

        $this->assertEquals(
            $_POST["position"],
            $updatedModel->getOrder()
        );
        $this->assertEquals(
            $_POST["description"],
            $updatedModel->getDescription()
        );
        $this->assertEquals(
            $_POST["path"],
            $updatedModel->getPath()
        );
    }

    public function testDeletePostReturnsTrue()
    {
        $controller = new GalleryImageController();
        $_POST["position"] = "1";
        $_POST["path"] = "foo.jpg";
        $_POST["description"] = "Hello World";
        $_POST["id"] = $controller->_createPost();

        $this->assertTrue($controller->_delete());
    }

    public function testDeletePostReturnsFalse()
    {
        $controller = new GalleryImageController();
        $_POST["position"] = "1";
        $_POST["path"] = "foo.jpg";
        $_POST["description"] = "Hello World";
        $_POST["id"] = PHP_INT_MAX;

        $this->assertFalse($controller->_delete());
    }
    public function testDeleteThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No id set");
        $controller = new GalleryImageController();
        $controller->_delete();
    }
}
