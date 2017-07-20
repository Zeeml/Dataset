<?php

namespace Zeeml\DataSet\Processor;

use Zeeml\DataSet\DataSet;

class ArrayProcessor extends AbstractProcessor
{
    public function __construct(array $source = [])
    {
        parent::__construct($source);
    }

    /**
     * Fills in the dataSet from the array sent in the construct
     * [
     *      [1, 2, 3, ...],
     *      [1, 2, 3, ...],
     *      [1, 2, 3, ...],
     * ]
     *
     * all the elements must be scalars (int, float, string)
     *
     * @return ProcessorInterface
     */
    public function read(): array
    {
        if ( isset($this->data)) {
            return $this->data;
        }

        $this->data = [];
        foreach ($this->source as &$row) {
            //checking if the source corresponds to the wanted format
            if (array_filter($row, 'is_scalar') == $row) {
                $this->data[] = $row;
            }
        }

        return $this->data;
    }

    public function write(): bool
    {
        return true;
    }
}
