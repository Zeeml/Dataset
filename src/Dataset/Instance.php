<?php

namespace Zeeml\Dataset\Dataset;

class Instance
{
    protected $inputs;
    
    protected $outputs;
    
    protected $results;
    
    public function __construct(array $inputs, array $outputs)
    {
        $this->inputs = $inputs;
        $this->outputs = $outputs;
    }
    
    public function inputs() : array
    {
        return $this->inputs;
    }
    
    public function outputs() : array
    {
        return $this->outputs;
    }
    
    public function result($result)
    {
        $this->results[] = $result;
        return $this;
    }
    
    public function results() : array
    {
        return $this->results;
    }
}
