<?php

namespace Zeeml\Dataset;

use Zeeml\Dataset\Processor\ProcessorInterface;

class AbstractDataset implements DatasetInterface
{
    protected $data;
    
    protected $processor;
    
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
    
    public function processor()
    {
        return $this->processor;
    }
    
    public function get()
    {
        return $this->processor->data();
    }
    
    public function size()
    {
        return $this->processor->size();
    }
}

