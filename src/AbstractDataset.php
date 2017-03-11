<?php

namespace Zeeml\Dataset;

use Zeeml\Dataset\Processor\ProcessorInterface;
use Zeeml\Dataset\Dataset\Instance;

class AbstractDataset implements DatasetInterface, \Iterator
{
    protected $data;
    
    protected $position;
    
    protected $instances;
    
    protected $processor;
    
    protected $inputs;
    
    protected $outputs;
    
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
     * @param int $dimensions
     * @param int $outputs
     */
    public function prepare(int $dimensions = 1, int $outputs = 1)
    {
        // @todo check that $input+$outputs matches data columns
        
        $this->data = $this->get();
        $this->instances = [];
        foreach ($this->data as $val) {
            $this->instances[] = new Instance(
                array_slice($val, 0, $dimensions),
                array_slice($val, $dimensions, $outputs)
            );
        }
    }
    
    public function instances()
    {
        return $this->instances;
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

