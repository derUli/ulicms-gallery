<?php

declare(strict_types=1);

use Gallery2019\Gallery;
use UliCMS\Exceptions\FileNotFoundException;

class GalleryController extends Controller
{
    public function createPost(): void
    {
        $id = $this->_createPost();
        Response::redirect(
            ModuleHelper::buildActionURL("gallery_edit", "id={$id}")
        );
    }

    public function _createPost(): ?int
    {
        $gallery = new Gallery();
        $gallery->setTitle(Request::getVar("title"));
        $gallery->setCreatedBy(get_user_id());
        $gallery->setLastChangedBy(get_user_id());
        $gallery->save();
        return $gallery->getID();
    }

    public function editPost(): void
    {
        $id = Request::getVar("id", 0, "int");
        $model = $this->_editPost();
        if ($model->isPersistent()) {
            Response::redirect(ModuleHelper::buildActionURL(
                "gallery_edit",
                "id={$model->getId()}&save=1"
            ));
        } else {
            throw new FileNotFoundException("No gallery with id {$id}");
        }
    }

    public function _editPost(): ?Gallery
    {
        $id = Request::getVar("id", 0, "int");
        $title = Request::getVar("title", "", "str");
        $model = new Gallery($id);
        if ($id and $model->getID()) {
            $model->setTitle($title);
            $model->save();
        }
        return $model;
    }

    public function delete(): void
    {
        $model = $this->_delete();
        if ($model) {
            Response::redirect(ModuleHelper::buildAdminURL(Gallery2019Controller::MODULE_NAME));
        } else {
            throw new FileNotFoundException("No gallery with id {$id}");
        }
    }

    public function _delete(): bool
    {
        $id = Request::getVar("id", 0, "int");
        if (!$id) {
            throw new Exception("No id set");
        }
        $model = new Gallery($id);
        if ($model->getID()) {
            $model->delete();
            return true;
        } else {
            return false;
        }
    }
}
