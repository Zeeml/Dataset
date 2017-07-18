<?php

namespace Zeeml\DataSet\Processor;

interface ProcessorInterface
{
    public function read(): ProcessorInterface;
    
    public function write(): bool;
}
