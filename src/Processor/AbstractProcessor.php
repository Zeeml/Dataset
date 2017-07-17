<?php

namespace Zeeml\Dataset\Processor;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected $uri;
    
    protected $data;
    
    public function __construct($uri = null)
    {
        $this->uri = $uri;
    }
    
    public function data()
    {
        $this->populate();
        return $this->data;
    }
    
    public function size()
    {
        $this->populate();
        return count($this->data());
    }

    public function populate()
    {
        if (is_null($this->data)) {
            $this->read();
        }
        
        return $this;
    }
    
    
    /**
     *
     * {@inheritdoc}
     *
     * @see \Zeeml\Dataset\Processor\ProcessorInterface::read()
     */
    abstract public function read();
    
    abstract public function write();
}
