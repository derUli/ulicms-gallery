<?php

declare(strict_types=1);

use Gallery2019\Gallery;
use Gallery2019\Image;
use UliCMS\Exceptions\FileNotFoundException;

class GalleryImageController extends Controller
{
    public function createPost(): void
    {
        $gallery_id = Request::getVar("gallery_id", null, "int");
        
        $path = Request::getVar("path");
        
        if (!$gallery_id or StringHelper::isNullOrWhitespace($path)) {
            ExceptionResult(get_translation("fill_all_fields"));
        }
        
        $gallery = new Gallery($gallery_id);
        if (!$gallery->getID()) {
            ExceptionResult("No gallery with id {$gallery_id}");
        }

        
        $id = $this->_createPost();
        Response::redirect(
            ModuleHelper::buildActionURL(
                "gallery_edit",
                "id={$gallery_id}"
            )
        );
    }

    public function _createPost(): ?int
    {
        $gallery_id = Request::getVar("gallery_id", null, "int");
        $path = Request::getVar("path");
        $description = Request::getVar("description", "", "str");
        $order = Request::getVar("position", 0, "int");

        $gallery = new Gallery($gallery_id);
        
        $image = new Image();
        $image->setPath($path);
        $image->setDescription($description);
        $image->setOrder($order);

        $gallery->addImage($image);

        return $image->getId();
    }

    public function editPost(): void
    {
        $gallery_id = Request::getVar("gallery_id", null, "int");
        $path = Request::getVar("path");
        
        if (!$gallery_id or StringHelper::isNullOrWhitespace($path)) {
            ExceptionResult(get_translation("fill_all_fields"));
        }
        
        $this->_editPost();

        Response::redirect(ModuleHelper::buildActionURL("gallery_edit", "id={$gallery_id}"));
    }

    public function _editPost(): ?Image
    {
        $id = Request::getVar("id", null, "int");

        $image = new Image($id);
        
        $path = Request::getVar("path");
        $description = Request::getVar("description", "", "str");
        $order = Request::getVar("position", 0, "int");

        $image->setPath($path);
        $image->setDescription($description);
        $image->setOrder($order);
        $image->save();
        return $image;
    }

    public function delete(): void
    {
        $id = Request::getVar("id", 0, "int");

        $model = new Image($id);
        +
                $gallery_id = $model->getGalleryId();

        $deleted = $this->_delete();
        if ($deleted) {
            Response::redirect(
                ModuleHelper::buildActionURL(
                    "gallery_edit",
                    "id={$gallery_id}"
                )
            );
        } else {
            throw new FileNotFoundException("No image with id {$id}");
        }
    }

    public function _delete(): bool
    {
        $id = Request::getVar("id", 0, "int");
        if (!$id) {
            throw new Exception("No id set");
        }

        $model = new Image($id);
        if ($model->getID()) {
            $model->delete();
            return true;
        } else {
            return false;
        }
    }
}
