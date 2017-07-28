<?php

namespace Zeeml\DataSet;

use Zeeml\DataSet\Processor\ProcessorInterface;
use Zeeml\DataSet\Exception\DataSetPreparationException;
use Zeeml\DataSet\Core\Mapper;

class DataSet implements \Iterator
{
    protected $data;
    protected $size;
    protected $position;
    protected $instances;
    protected $mapper;
    protected $dimensionsMatrix;
    protected $outputMatrix;
    protected $isPrepared;

    public function __construct(ProcessorInterface $processor)
    {
        $this->data = $processor->read();
        $this->size = count($this->data);
        $this->position = 0;
        $this->isPrepared = false;
    }

    /**
     * Prepare data to be trained. MUST be called prior to any call
     * @param Mapper $mapper Data Mapper
     * @param bool $preserveKeys set to true to preserve the keys of the dimensions, false to reset them
     * @throws DataSetPreparationException
     */
    public function prepare(Mapper $mapper, bool $preserveKeys = true)
    {
        $this->mapper = $mapper;
        $this->instances = $this->dimensionsMatrix = $this->outputMatrix = [];
        foreach ($this->data as &$row) {
            $instance = $this->mapper->createInstance($row, $preserveKeys);
            $this->instances[] = $instance;
            $this->dimensionsMatrix[] = $instance->getDimensions();
            $this->outputMatrix[] = $instance->getOutputs();
        }
        $this->isPrepared = true;
    }

    public function clean()
    {
        foreach ($this->data as &$row) {

        }
    }

    /**
     * Getter for data
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * returns the size of the dataSet
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Getter for the mapper used
     * @return Mapper
     * @throws DataSetPreparationException
     */
    public function getMapper(): Mapper
    {
        if (! $this->isPrepared()) {
            throw new DataSetPreparationException("prepare() method must be called prior any call");
        }

        return $this->mapper;
    }

    /**
     * Return an array of data instances
     * @throws DataSetPreparationException
     * @return array
     */
    public function getInstances() : array
    {
        if (! $this->isPrepared()) {
            throw new DataSetPreparationException("prepare() method must be called prior any call");
        }

        return $this->instances;
    }

    /**
     * returns the prepared dimensions in an array
     * @return array
     * @throws DataSetPreparationException
     */
    public function getDimensionsMatrix(): array
    {
        if (! $this->isPrepared()) {
            throw new DataSetPreparationException("prepare() method must be called prior any call");
        }

        return $this->dimensionsMatrix;
    }

    /**
     * returns the prepared outputs in an array
     * @return array
     * @throws DataSetPreparationException
     */
    public function getOutputMatrix(): array
    {
        if (! $this->isPrepared()) {
            throw new DataSetPreparationException("prepare() method must be called prior any call");
        }

        return $this->outputMatrix;
    }

    public function isPrepared(): bool
    {
        return $this->isPrepared;
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

