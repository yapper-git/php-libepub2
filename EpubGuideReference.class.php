<?php

require_once __DIR__.'/HydratableObject.class.php';

class EpubGuideReference extends HydratableObject
{
    protected $type;
    protected $title;
    protected $href;
    
    public function isValid()
    {
        return isset($this->type, $this->title, $this->href);
    }
    
    public function valid()
    {
        if (!$this->isValid()) {
            throw new \Exception(
                'GuideReference: The attributes type, title and href are required'
            );
        }
    }
    
    public function setType($type)
    {
        if (!is_string($type) or empty($type)) {
            throw new \InvalidArgumentException(
                'GuideReference: The type attribute must be a valid string'
            );
        }
        
        $this->type = $type;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function setTitle($title)
    {
        if (!is_string($title) or empty($title)) {
            throw new \InvalidArgumentException(
                'GuideReference: The title attribute must be a valid string'
            );
        }
        
        $this->title = $title;
    }
    
    public function title()
    {
        return $this->title;
    }
    
    public function setHref($href)
    {
        if (!is_string($href) or empty($href)) {
            throw new \InvalidArgumentException(
                'GuideReference: The href attribute must be a valid string'
            );
        }
        
        $this->href = $href;
    }
    
    public function href()
    {
        return $this->href;
    }
}
