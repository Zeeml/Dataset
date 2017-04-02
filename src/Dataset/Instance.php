<?php

namespace Zeeml\Dataset\Dataset;

class Instance
{
    /**
     * 
     * @var array
     */
    protected $dimensions;
    
    /**
     * 
     * @var array
     */
    protected $outputs;
    
    /**
     * 
     * @var array
     */
    protected $results;
    
    /**
     * Class constructor
     * @param array $dimensions
     * @param array $outputs
     */
    public function __construct(array $dimensions, array $outputs)
    {
        $this->dimensions = $dimensions;
        $this->outputs = $outputs;
    }
    
    /**
     * Return the instance dimensions as an array
     * @return array
     */
    public function dimensions() : array
    {
        return $this->dimensions;
    }
    
    /**
     * Return the instance outputs (predictions or classifications) as an array
     * @return array
     */
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
