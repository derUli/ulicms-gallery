<?php

declare(strict_types=1);

namespace Gallery2019;

use Model;
use Database;
use Exception;

class Gallery extends Model
{
    private $title;

    private $created;

    private $updated;

    private $createdby;

    private $lastchangedby;

    public function loadByID($id)
    {
        $sql = "select * from `{prefix}gallery` where id = ?";
        $args = array(
            intval($id)
        );
        $query = Database::pQuery($sql, $args, true);
        $this->fillVars($query);
    }

    protected function fillVars($query = null)
    {
        if ($query and Database::getNumRows($query) > 0) {
            $result = Database::fetchObject($query);
            $this->setID($result->id);
            $this->title = $result->title;
            $this->created = strtotime($result->created);
            $this->updated = strtotime($result->updated);
            $this->createdby = intval($result->createdby);
            $this->lastchangedby = intval($result->lastchangedby);
        } else {
            $this->setID(null);
            $this->title = null;
            $this->created = null;
            $this->updated = null;
            $this->createdby = null;
            $this->lastchangedby = null;
        }
    }

    protected function insert()
    {
        $time = time();
        $this->setCreated($time);
        $this->setUpdated($time);
        $sql = "insert into `{prefix}gallery` 
                (
                    title, 
                    created, 
                    updated, 
                    createdby, 
                    lastchangedby
                )
                values
                (
                    ?,
                    FROM_UNIXTIME(?),
                    FROM_UNIXTIME(?),
                    ?,
                    ?
                )";
        $args = array(
            $this->getTitle(),
            $this->getCreated(),
            $this->getUpdated(),
            $this->getCreatedBy(),
            $this->getlastchangedby()
        );
        Database::pQuery($sql, $args, true);
        $this->setID(Database::getLastInsertID());
    }

    protected function update()
    {
        $this->setUpdated(time());
        $sql = "update `{prefix}gallery` set
                title = ?,
                created = FROM_UNIXTIME(?),
                updated = FROM_UNIXTIME(?),
                createdby = ?,
                lastchangedby = ?
                where id = ?
                ";
        $args = array(
            $this->getTitle(),
            $this->getCreated(),
            $this->getUpdated(),
            $this->getCreatedBy(),
            $this->getlastchangedby(),
            $this->getID()
        );
        Database::pQuery($sql, $args, true);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $val): void
    {
        $this->title = $val;
    }

    public function getCreated():?int
    {
        return $this->created;
    }

    public function setCreated(?int $val): void
    {
        $this->created = $val;
    }

    public function getUpdated(): ?int
    {
        return $this->updated;
    }

    public function setUpdated(?int $val)
    {
        $this->updated = $val;
    }

    public function getCreatedBy():?int
    {
        return $this->createdby;
    }

    public function setCreatedBy(?int $val)
    {
        $this->createdby = $val;
    }

    public function getLastChangedBy(): ?int
    {
        return $this->lastchangedby;
    }

    public function setLastChangedBy($val)
    {
        $this->lastchangedby = is_numeric($val) ? intval($val) : null;
    }

    public function delete(): void
    {
        if (! $this->getID()) {
            return;
        }
        $sql = "delete from `{prefix}gallery` where id = ?";
        $args = array(
            $this->getID()
        );
        Database::pQuery($sql, $args, true);
        $this->fillVars(null);
    }

    public function getImages(): array
    {
        $result = [];
        if (! $this->getID()) {
            return $result;
        }
        $sql = "select id from `{prefix}gallery_images` where gallery_id = ? order by `order`,id";
        $args = array(
            $this->getID()
        );
        $query = Database::pQuery($sql, $args, true);
        while ($row = Database::fetchObject($query)) {
            $result[] = new Image($row->id);
        }
        return $result;
    }

    public function addImage(Image $image): void
    {
        if (! $this->getID()) {
            throw new Exception("The Gallery must be saved before you can add images");
        }
        $image->setGalleryId($this->getID());
        $image->save();
    }

    public static function getAll(): array
    {
        $result = [];
        $sql = "select id from `{prefix}gallery` order by id";
        $query = Database::query($sql, true);
        while ($row = Database::fetchObject($query)) {
            $result[] = new self($row->id);
        }
        return $result;
    }
}
