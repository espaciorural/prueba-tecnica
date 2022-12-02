<?php
// src/Entity/Task.php
namespace App\Entity;

class Task
{
    public $tag;
    public $fromDate;
    public $toDate;

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->task = $tag;
    }

    public function getFromDate(): ?\DateTime
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }
    public function getToDate(): ?\DateTime
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTime $toDate): void
    {
        $this->toDate = $toDate;
    }
}