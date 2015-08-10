<?php

require_once __DIR__.'/EpubNavPoint.class.php';

class EpubToc
{
    protected $navPoints;

    public function __construct()
    {
        $this->navPoints = array();
    }

    public function valid()
    {
        if (empty($this->navPoints)) {
            throw new \Exception('EpubToc: You must add at least one navPoint');
        }

        foreach ($this->navPoints as $navPoint) {
            $navPoint->valid();
        }
    }

    public function append(EpubNavPoint $navPoint)
    {
        $this->navPoints[] = $navPoint;
    }

    public function navPoints()
    {
        return $this->navPoints;
    }

    public function depth()
    {
        $maxDepth = 0;

        foreach ($this->navPoints as $navPoint) {
            $depth = $this->depthRec($navPoint, 1);

            if ($depth > $maxDepth) {
                $maxDepth = $depth;
            }
        }

        return $maxDepth;
    }

    protected function depthRec(EpubNavPoint $navPoint, $currentDepth)
    {
        $navPoints = $navPoint->navPoints();

        if (empty($navPoints)) {
            return $currentDepth;
        } else {
            if ($currentDepth > 20) {
                throw new \Exception(
                    'Toc: The table of contents is infinite! '.
                    '(maximum function nesting level reached)'
                );
            }

            $max = $currentDepth;
            foreach ($navPoints as $subNavPoint) {
                $depth = $this->depthRec($subNavPoint, $currentDepth+1);

                if ($depth > $max) {
                    $max = $depth;
                }
            }
            return $max;
        }
    }
}
