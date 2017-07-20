<?php

namespace Zeeml\DataSet\Processor;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected $source;
    protected $data;

    public function __construct($source)
    {
        $this->source = $source;
    }
}
