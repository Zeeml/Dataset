<?php

namespace Zeeml\DataSet\Core;

use Zeeml\DataSet\Core\Result\ResultInterface;

/**
 * Class Instance that represents a single row of a dataSet
 * @package Zeeml\Core\Core
 */
class Instance
{
    protected $original;
    protected $inputs;
    protected $outputs;
    protected $results = [];

    /**
     * Class constructor
     * @param array $inputs
     * @param array $outputs
     */
    public function __construct(array $inputs, array $outputs)
    {
        $this->inputs = $inputs;
        $this->outputs = $outputs;
    }

    /**
     * Return the instance inputs as an array
     * @return array
     */
    public function getInputs() : array
    {
        return $this->inputs;
    }

    /**
     * Return the instance inputs as an array
     * @param mixed $key
     * @return mixed|null
     */
    public function getInput($key)
    {
        return $this->inputs[$key] ?? null;
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
     * @param mixed $key
     * @return array
     */
    public function getOutput($key)
    {
        return $this->outputs[$key] ?? null;
    }

    /**
     * @param string $key
     * @param ResultInterface $result
     * @return $this
     */
    public function addResult(string $key, ResultInterface $result)
    {
        $this->results[$key] = $result;

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
     * @return ResultInterface|null
     */
    public function getResult(string $key)
    {
        return $this->results[$key] ?? null;
    }
}
