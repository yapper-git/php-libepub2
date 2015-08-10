<?php

require_once __DIR__.'/HydratableObject.class.php';

class EpubNavPoint extends HydratableObject
{
    protected $id;
    protected $label;
    protected $source;
    protected $navPoints;

    public function __construct(array $data = array())
    {
        $this->navPoints = array();

        parent::__construct($data);
    }

    public function isValid()
    {
        foreach ($this->navPoints as $navPoint) {
            if (!$navPoint->isValid()) {
                return false;
            }
        }

        return isset($this->id, $this->label, $this->source);
    }

    public function valid()
    {
        if (!$this->isValid()) {
            throw new \Exception(
                'NavPoint: The attributes id, label and source are required'
            );
        }
    }

    public function setId($id)
    {
        if (!is_string($id) or empty($id)) {
            throw new \InvalidArgumentException(
                'NavPoint: The id attribute must be a valid string'
            );
        }

        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function setLabel($label)
    {
        if (!is_string($label) or empty($label)) {
            throw new \InvalidArgumentException(
                'NavPoint: The label attribute must be a valid string'
            );
        }

        $this->label = $label;
    }

    public function label()
    {
        return $this->label;
    }

    public function setSource($source)
    {
        if (!is_string($source) or empty($source)) {
            throw new \InvalidArgumentException(
                'NavPoint: The source attribute must be a valid string'
            );
        }

        $this->source = $source;
    }

    public function source()
    {
        return $this->source;
    }

    public function setNavPoints(array $navPoints)
    {
        $this->navPoints = $navPoints;
    }

    public function append(EpubNavPoint $navPoint)
    {
        $this->navPoints[] = $navPoint;
    }

    public function navPoints()
    {
        return $this->navPoints;
    }
}
