<?php

namespace Zeeml\DataSet\DataSet;

/**
 * Class Instance that represents a single row of a dataSet
 * @package Zeeml\DataSet\DataSet
 */
class Instance
{
    protected $dimensions;
    protected $outputs;
    protected $results = [];

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
    public function getDimensions() : array
    {
        return $this->dimensions;
    }

    /**
     * Return the instance dimensions as an array
     * @param int $index
     * @return mixed|null
     */
    public function getDimension(int $index)
    {
        return $this->dimensions[$index] ?? null;
    }

    /**
     * Return the instance outputs (predictions or classifications) as an array
     * @return array
     */
    public function getOutputs() : array
    {
        return $this->outputs;
    }

    /**
     * Return the instance outputs (predictions or classifications) as an array
     * @param int $index
     * @return array
     */
    public function getOutput(int $index)
    {
        return $this->outputs[$index] ?? null;
    }

    /**
     * @param string $key
     * @param $result
     * @return $this
     */
    public function addResult(string $key, $result)
    {
        $this->results[$key] = array_merge($this->results[$key] ?? [], $result);

        return $this;
    }

    /**
     * returns all hte results
     * @return array
     */
    public function getResults() : array
    {
        return $this->results;
    }

    /**
     * returns a result for a given key
     * @param string $key
     * @return null
     */
    public function getResult(string $key)
    {
        return $this->results[$key] ?? null;
    }
}
