<?php

class EpubMetadata
{
    protected $titles;
    protected $creators;
    protected $subjects;
    protected $description;
    protected $publisher;
    protected $contributors;
    protected $dates;
    protected $type;
    protected $format;
    protected $identifiers;
    protected $source;
    protected $languages;
    protected $relation;
    protected $coverage;
    protected $rights;
    protected $metas;

    public function __construct()
    {
        $this->titles       = array();
        $this->creators     = array();
        $this->subjects     = array();
        $this->description  = '';
        $this->publisher    = '';
        $this->contributors = array();
        $this->dates        = array();
        $this->type         = '';
        $this->format       = '';
        $this->identifiers  = array();
        $this->source       = '';
        $this->languages    = array();
        $this->relation     = '';
        $this->coverage     = '';
        $this->rights       = '';
        $this->metas        = array();
    }

    public function isValid()
    {
        return (!empty($this->titles) and !empty($this->identifiers) and !empty($this->languages));
    }

    public function valid()
    {
        if (!$this->isValid()) {
            throw new \Exception(
                'Metadata: You must add at least one title, one identifier '.
                'and one language'
            );
        }
    }

    public function addTitle($title)
    {
        $this->titles[] = (string) $title;
    }

    public function titles()
    {
        return $this->titles;
    }

    public function addCreator($name, $role = '', $file_as = '')
    {
        $this->creators[] = array(
            'name'    => (string) $name,
            'role'    => (string) $role,
            'file-as' => (string) $file_as
        );
    }

    public function creators()
    {
        return $this->creators;
    }

    public function addSubject($subject)
    {
        $this->subjects[] = (string) $subject;
    }

    public function subjects()
    {
        return $this->subjects;
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    public function description()
    {
        return $this->description;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = (string) $publisher;
    }

    public function publisher()
    {
        return $this->publisher;
    }

    public function addContributor($name, $role = '', $file_as = '')
    {
        $this->contributors[] = array(
            'name'    => (string) $name,
            'role'    => (string) $role,
            'file-as' => (string) $file_as
        );
    }

    public function contributors()
    {
        return $this->contributors;
    }

    public function addDate($date, $event = '')
    {
        $this->dates[] = array(
            'date'  => (string) $date,
            'event' => (string) $event
        );
    }

    public function dates()
    {
        return $this->dates;
    }

    public function setType($type)
    {
        $this->type = (string) $type;
    }

    public function type()
    {
        return $this->type;
    }

    public function setFormat($format)
    {
        $this->format = (string) $format;
    }

    public function format()
    {
        return $this->format;
    }

    public function addIdentifier($content, $id = 'BookId', $scheme = '')
    {
        $this->identifiers[] = array(
            'content' => (string) $content,
            'id'      => (string) $id,
            'scheme'  => (string) $scheme
        );
    }

    public function identifiers()
    {
        return $this->identifiers;
    }

    public function setSource($source)
    {
        $this->source = (string) $source;
    }

    public function source()
    {
        return $this->source;
    }

    public function addLanguage($lang)
    {
        $this->languages[] = (string) $lang;
    }

    public function languages()
    {
        return $this->languages;
    }

    public function setRelation($relation)
    {
        $this->relation = (string) $relation;
    }

    public function relation()
    {
        return $this->relation;
    }

    public function setCoverage($coverage)
    {
        $this->coverage = (string) $coverage;
    }

    public function coverage()
    {
        return $this->coverage;
    }

    public function setRights($rights)
    {
        $this->rights = (string) $rights;
    }

    public function rights()
    {
        return $this->rights;
    }

    public function addMeta($name, $content)
    {
        $this->metas[] = array(
            'name'    => (string) $name,
            'content' => (string) $content
        );
    }

    public function metas()
    {
        return $this->metas;
    }
}
