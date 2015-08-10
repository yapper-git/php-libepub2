<?php

require_once __DIR__.'/HydratableObject.class.php';

class EpubManifestItem extends HydratableObject
{
    protected $id;
    protected $href;
    protected $mediaType;

    public function isValid()
    {
        return isset($this->id, $this->href, $this->mediaType);
    }

    public function valid()
    {
        if (!$this->isValid()) {
            throw new \Exception(
                'ManifestItem: The attributes id, href and mediaType are required'
            );
        }
    }

    public function setId($id)
    {
        if (!is_string($id) or empty($id)) {
            throw new \InvalidArgumentException(
                'ManifestItem: The id attribute must be a valid string'
            );
        }

        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function setHref($href)
    {
        if (!is_string($href) or empty($href)) {
            throw new \InvalidArgumentException(
                'ManifestItem: The href attribute must be a valid string'
            );
        }

        $this->href = $href;
    }

    public function href()
    {
        return $this->href;
    }

    public function setMediaType($mediaType)
    {
        if (!is_string($mediaType) or empty($mediaType)) {
            throw new \InvalidArgumentException(
                'ManifestItem: The mediaType attribute must be a valid string'
            );
        }

        $this->mediaType = $mediaType;
    }

    public function mediaType()
    {
        return $this->mediaType;
    }
}
