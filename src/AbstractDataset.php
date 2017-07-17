<?php

namespace Zeeml\Dataset;

use Zeeml\Dataset\Processor\ProcessorInterface;
use Zeeml\Dataset\Dataset\Instance;
use Zeeml\Dataset\Exception\DatasetPreparationException;
use Zeeml\Dataset\Dataset\Mapper;

class AbstractDataset implements DatasetInterface, \Iterator
{
    protected $data;
    
    protected $position;
    
    protected $instances;
    
    protected $processor;
    
    protected $dimensions;
    
    protected $outputs;
    
    protected $mapper;
    
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
    {   $this->mapper = $mapper;
        $this->data = $this->get();
        $this->instances = $this->mapper->instancesFactory($this->data, $preserveKeys);
    }
    
    /**
     * Return an array of data instances
     * @throws DatasetPreparationException
     * @return array
     */
    public function instances() : array
    {
        if (! is_array($this->instances)) {
            throw new DatasetPreparationException("prepare() method must be called prior any instances() call");
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

