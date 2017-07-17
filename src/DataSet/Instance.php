<?php

namespace Zeeml\DataSet\DataSet;

class Instance
{
    protected $dimensions;
    protected $outputs;
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

    /**
     * @param string $key
     * @param $result
     * @return $this
     */
    public function addResult(string $key, $result)
    {
        $this->results[$key] = $result;

        return $this;
    }
    
    public function results() : array
    {
        return $this->results;
    }
}
