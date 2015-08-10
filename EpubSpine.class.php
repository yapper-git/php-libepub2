<?php

class EpubSpine
{
    protected $itemRefs;
    protected $toc;

    public function __construct()
    {
        $this->itemRefs = array();
    }

    public function isValid()
    {
        if (empty($this->toc)) {
            return false;
        }

        if (empty($this->itemRefs)) {
            return false;
        }

        foreach ($this->itemRefs as $itemRef) {
            if (!$itemRef->isValid()) {
                return false;
            }
        }

        return true;
    }

    public function valid()
    {
        if (empty($this->toc)) {
            throw new \Exception(
                'Spine: You must specify toc'
            );
        }

        if (empty($this->itemRefs)) {
            throw new \Exception(
                'Spine: You must add at least one (item/id) reference'
            );
        }
    }

    public function setToc($toc)
    {
        if (!is_string($toc) or empty($toc)) {
            throw new \InvalidArgumentException(
                'Spine: The toc attribute must be a valid string'
            );
        }

        $this->toc = $toc;
    }

    public function toc()
    {
        return $this->toc;
    }

    public function append($idref)
    {
        $this->itemRefs[] = (string) $idref;
    }

    public function itemRefs()
    {
        return $this->itemRefs;
    }
}
