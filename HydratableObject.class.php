<?php

class HydratableObject
{
    public function __construct(array $data = array())
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate(array $data)
    {
        foreach ($data as $attr => $value) {
            $method = 'set'.ucfirst($attr);

            if (is_callable(array($this, $method))) {
                $this->$method($value);
            } else {
                throw new \RuntimeException(
                    'The attribute \''.$attr.'\' can not be hydrated'
                );
            }
        }
    }
}
