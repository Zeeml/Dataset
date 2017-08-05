<?php

namespace Zeeml\DataSet;

use Zeeml\DataSet\Core\CleanPolicy;
use Zeeml\DataSet\Core\Instance;
use Zeeml\DataSet\Processor\ProcessorInterface;
use Zeeml\DataSet\Exception\DataSetPreparationException;
use Zeeml\DataSet\Core\Mapper;

class DataSet implements \Iterator
{
    protected $rawData;
    protected $rawAverage;
    protected $rawSize;
    protected $size;
    protected $position;
    protected $instances;
    protected $mapper;
    protected $inputsMatrix;
    protected $outputsMatrix;
    protected $isPrepared;
    protected $average;
    protected $frequency;

    public function __construct(ProcessorInterface $processor)
    {
        $this->rawData = $processor->read();
        $this->rawSize = count($this->rawData);
        $this->rawAverage = [];
        $this->position = 0;
        $this->isPrepared = false;
        $this->instances = [];
        $this->inputsMatrix = [];
        $this->outputsMatrix = [];
        $this->size = 0;
    }

    /**
     * Prepare data to be trained. MUST be called prior to any call
     * @param Mapper $mapper Data Mapper
     * @throws DataSetPreparationException
     */
    public function prepare(Mapper $mapper)
    {
        if ($this->isPrepared()) {
            throw new DataSetPreparationException('DataSet is already prepared');
        }
        //Split each row of the dataSet into inputs and outputs based on the mapper
        $this->map($mapper);
        //Then re-parse the dataSet to fill in all the fields that used special policies (like Avg) and create the instances
        $this->createInstances();
        //mark the dataSet as prepared
        $this->isPrepared = true;
    }

    /**
     * Is called by the prepare method, maps the data grabbed by the processor into instances objects
     * @param Mapper $mapper
     * @return DataSet
     */
    protected function map(Mapper $mapper): DataSet
    {
        $this->mapper = $mapper;
        $this->instances = $this->inputsMatrix = $this->outputsMatrix = [];

        foreach ($this->rawData as &$row) {
            //Split each row of the raw DataSet into inputs and outputs
            list($inputs, $outputs) = $this->mapper->map($row);
            //If the mapping was successfully made (the cleaners allowed the row to pass)
            if ($inputs && $outputs) {
                //Increment size
                $this->size++;
                //Save the inputs matrix
                $this->inputsMatrix[] = $inputs;
                //save the output matrix
                $this->outputsMatrix[] = $outputs;
            }

            foreach ($row as $key => $value) {
                //calculate the frequency at which each value occurs
                $this->frequency[$key][$value] = $this->frequency[$key][$value] ?? 0;
                $this->frequency[$key][$value]++;

                //calculate the rawAverage of each column of the raw DataSet, since cleaners might ignore a row
                $this->rawAverage[$key] = $this->rawAverage[$key] ?? 0;
                $this->rawAverage[$key] += floatval($value) / $this->rawSize;
            }

        }

        return $this;
    }

    /**
     * Create the instance based on the clean inputs and outputs
     * Re-parse the dataSet to replace all the values that were cleaned with CleanPolicy::replaceWithAvg with the mean
     * Create the instances
     *
     * @return DataSet
     */
    protected function createInstances(): DataSet
    {
        //calculating the new Average for the new DataSet (which might differ from the raw one depending on cleaning policies applied)
        $this->average = [];
        //got through inputs matrix
        foreach ($this->inputsMatrix as $index => &$inputs) {

            foreach ($inputs as $dimKey => &$value) {
                if ($value === CleanPolicy::AVG) {
                    $value = $this->rawAverage[$dimKey];
                } elseif ($value === CleanPolicy::MOST_COMMON) {
                    $value = array_search(max($this->frequency[$dimKey]), $this->frequency[$dimKey]) ? : 0;
                }
                $this->average[0][$dimKey] = $this->average[0][$dimKey] ?? 0;
                $this->average[0][$dimKey] += floatval($value) / $this->size;
            }

            foreach ($this->outputsMatrix[$index] as $outputKey => &$value) {
                if ($value === CleanPolicy::AVG) {
                    $value = $this->rawAverage[$outputKey];
                } elseif ($value === CleanPolicy::MOST_COMMON) {
                    $value = array_search(max($this->frequency[$outputKey]), $this->frequency[$outputKey]) ? : 0;
                }

                $this->average[1][$outputKey] = $this->average[1][$outputKey] ?? 0;
                $this->average[1][$outputKey] += floatval($value) / $this->size;
            }

            $this->instances[$index] = new Instance($this->inputsMatrix[$index], $this->outputsMatrix[$index]);
        }

        return $this;
    }

    public function rename(array $mapping)
    {
        $this->needsPreparation();

        foreach ($this->instances as $index => $instance) {
            foreach ($mapping as $oldKey => $newKey) {
                $instance->renameInput($oldKey, $newKey);
                $instance->renameOutput($oldKey, $newKey);
                if (array_key_exists($oldKey, $this->inputsMatrix[$index])) {
                    $this->inputsMatrix[$index][$newKey] = $this->inputsMatrix[$index][$oldKey];
                    unset($this->inputsMatrix[$index][$oldKey]);
                }
                if (array_key_exists($oldKey, $this->outputsMatrix[$index])) {
                    $this->outputsMatrix[$index][$newKey] = $this->outputsMatrix[$index][$oldKey];
                    unset($this->outputsMatrix[$index][$oldKey]);
                }
            }
        }
    }

    /**
     * Getter for data
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }

    /**
     * returns the size of the dataSet
     * @return int
     * @throws DataSetPreparationException
     */
    public function getSize(): int
    {
        $this->needsPreparation();

        return $this->size;
    }

    /**
     * Getter for the mapper used
     * @return Mapper
     * @throws DataSetPreparationException
     */
    public function getMapper(): Mapper
    {
        $this->needsPreparation();

        return $this->mapper;
    }

    /**
     * Return an array of data instances
     * @throws DataSetPreparationException
     * @return array
     */
    public function getInstances() : array
    {
        $this->needsPreparation();

        return $this->instances;
    }

    /**
     * returns the prepared inputs in an array
     * @return array
     * @throws DataSetPreparationException
     */
    public function getInputsMatrix(): array
    {
        $this->needsPreparation();

        return $this->inputsMatrix;
    }

    /**
     * returns the prepared outputs in an array
     * @return array
     * @throws DataSetPreparationException
     */
    public function getOutputsMatrix(): array
    {
        $this->needsPreparation();

        return $this->outputsMatrix;
    }

    public function getInputsAvg()
    {
        return $this->average[0];
    }

    public function getInputAvg($inputIndex)
    {
        return $this->average[0][$inputIndex] ?? null;
    }

    public function getOutputsAvg()
    {
        return $this->average[1];
    }

    public function getOutputAvg($outputIndex)
    {
        return $this->average[1][$outputIndex] ?? null;
    }

    public function isPrepared(): bool
    {
        return $this->isPrepared;
    }

    /**
     * @throws DataSetPreparationException
     */
    protected function needsPreparation()
    {
        if (! $this->isPrepared()) {
            throw new DataSetPreparationException("prepare() method must be called prior any call");
        }
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

