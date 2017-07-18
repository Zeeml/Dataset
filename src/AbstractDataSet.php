<?php

namespace Zeeml\DataSet;

use Zeeml\Algorithms\AlgorithmsInterface;
use Zeeml\DataSet\Processor\ProcessorInterface;
use Zeeml\DataSet\DataSet\Instance;
use Zeeml\DataSet\Exception\DataSetPreparationException;
use Zeeml\DataSet\DataSet\Mapper;

class AbstractDataSet implements DataSetInterface, \Iterator
{
    protected $data;
    protected $position;
    protected $instances;
    protected $processor;
    protected $mapper;
    protected $rawDimensions = [];
    protected $rawOutputs = [];
    protected $algorithms = [];


    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
        $this->position = 0;
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
    
    /**
     * Prepare data to be trained
     * @param Mapper $mapper Data Mapper
     * @param bool $preserveKeys whether to preserve data keys (default is false)
     */
    public function prepare(Mapper $mapper, bool $preserveKeys = false)
    {
        $this->mapper = $mapper;
        $this->data = $this->get();
        foreach ($this->data as $key => &$row) {
            $instance = $this->mapper->instanceFactory($row, $key, $preserveKeys);
            $this->instances[] = $instance;
            $this->rawDimensions[] = $instance->dimensions();
            $this->rawOutputs[] = $instance->outputs();
        }
    }
    
    /**
     * Return an array of data instances
     * @throws DataSetPreparationException
     * @return array
     */
    public function instances() : array
    {
        if (! is_array($this->instances)) {
            throw new DataSetPreparationException("prepare() method must be called prior any instances() call");
        }
        
        return $this->instances;
    }
    
    /**
     * Return the Instance() instance in $instances matching key or false
     * @param int $key
     * @return boolean|Instance
     */
    public function instance(int $key)
    {
        return isset($this->instances[$key]) ? $this->instances[$key] : false;
    }

    /**
     * returns the list of all hte algorithms used on the dataSet
     * @return array
     */
    public function algorithms(): array
    {
        return $this->algorithms;
    }

    /**
     * returns the the algorithm used by name
     * @param string $algorithmName
     * @return AlgorithmsInterface if any, null otherwise
     */
    public function algorithm(string $algorithmName)
    {
        return $this->algorithms[$algorithmName] ?? null;
    }


    public function addAlgorithm(AlgorithmsInterface $algorithm): DataSetInterface
    {
        $this->algorithms[get_class($algorithm)] = $algorithm;

        return $this;
    }

    /**
     * returns the prepared dimensions in an array
     * @return array
     */
    public function rawDimensions(): array
    {
        return $this->rawDimensions;
    }

    /**
     * returns the prepared outputs in an array
     * @return array
     */
    public function rawOutputs(): array
    {
        return $this->rawOutputs;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Iterator::valid()
     */
    public function valid()
    {
        return isset($this->instances[$this->position]);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->instances[$this->position];
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }
}

