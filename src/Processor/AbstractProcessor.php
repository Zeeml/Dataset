<?php

namespace Zeeml\DataSet\Processor;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected $source;
    protected $data;
    protected $size = 0;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function data(): array
    {
        $this->populate();

        return $this->data;
    }
    
    public function size(): int
    {
        $this->populate();

        return $this->size;
    }

    public function populate(): ProcessorInterface
    {
        if (is_null($this->data)) {
            $this->read();
            $this->size = count($this->data?? []);
        }

        return $this;
    }
}
