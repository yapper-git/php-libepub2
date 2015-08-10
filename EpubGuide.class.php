<?php

require_once __DIR__.'/EpubGuideReference.class.php';

class EpubGuide
{
    protected $references;

    public function __construct()
    {
        $this->references = array();
    }

    public function valid()
    {
        foreach ($this->references as $reference) {
            $reference->valid();
        }
    }

    public function append(EpubGuideReference $reference)
    {
        $this->references[] = $reference;
    }

    public function references()
    {
        return $this->references;
    }
}
