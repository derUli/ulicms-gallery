<?php
use Gallery2019\Image;
use Gallery2019\Gallery;

class ImageModelTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Database::query("delete from `{prefix}gallery` where title like 'Test - %'", true);
    }
    
    public function testGetGalleryReturnsNull()
    {
        $image = new Image();
        $image->delete();
        $this->assertNull($image->getGallery());
    }
    public function testExistsReturnsFalse()
    {
        $image = new Image();
        $this->assertFalse($image->exists());
    }
   
    public function testGetGalleryReturnsGallery()
    {
        $manager = new UserManager();
        $users = $manager->getAllUsers();
        $firstUser = $users[0];
        
        $gallery = new Gallery();
        $gallery->setTitle("Test - Foo");
        $gallery->setCreatedBy($firstUser->getId());
        $gallery->setLastChangedBy($firstUser->getId());
        $gallery->save();
        
        $image = new Image();
        $image->setGalleryId($gallery->getId());
        $this->assertEquals($gallery->getId(), $image->getGallery()->getID());
    }
}
