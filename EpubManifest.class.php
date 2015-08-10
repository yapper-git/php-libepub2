<?php

require_once __DIR__.'/EpubManifestItem.class.php';

class EpubManifest
{
    protected $items;

    public function __construct()
    {
        $this->items = array();
    }

    public function valid()
    {
        if (empty($this->items)) {
            throw new \Exception('Manifest: You must add at least one item');
        }

        foreach ($this->items as $item) {
            $item->valid();
        }
    }

    public function append(EpubManifestItem $item)
    {
        $this->items[] = $item;
    }

    public function items()
    {
        return $this->items;
    }
}
