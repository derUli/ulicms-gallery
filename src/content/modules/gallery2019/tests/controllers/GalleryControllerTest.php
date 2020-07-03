<?php

class GalleryControllerTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $manager = new UserManager();
        $users = $manager->getAllUsers();
        $firstUser = $users[0];

        $_SESSION = [
            "login_id" => $firstUser->getId()
        ];
    }

    protected function tearDown(): void
    {
        $_POST = [];
        $_SESSION = [];
        Database::query("delete from `{prefix}gallery` where title like 'Test - %'", true);
    }

    public function testCreatePost()
    {
        $controller = new GalleryController();
        $_POST["title"] = "Test - " . time();
        $id = $controller->_createPost();
        $this->assertGreaterThanOrEqual(1, $id);
    }

    public function testUpdatePost()
    {
        $controller = new GalleryController();
        $_POST["title"] = "Test - " . time();

        $_POST["id"] = $controller->_createPost();
        $_POST["title"] = "Test - Foobar";
        $updatedModel = $controller->_editPost();

        $this->assertTrue($updatedModel->isPersistent());
        $this->assertEquals($_POST["id"], $updatedModel->getId());
        $this->assertEquals($_POST["title"], $updatedModel->getTitle());
    }

    public function testDeletePostReturnsTrue()
    {
        $controller = new GalleryController();

        $_POST["title"] = "Test - " . time();
        $_POST["id"] = $controller->_createPost();

        $this->assertTrue($controller->_delete());
    }

    public function testDeletePostReturnsFalse()
    {
        $controller = new GalleryController();
        $_POST["id"] = PHP_INT_MAX;
        $this->assertFalse($controller->_delete());
    }

    public function testDeletePostThrowsException()
    {
        $this->expectException(Exception::class);
        $controller = new GalleryController();
        $controller->_delete();
    }
}
