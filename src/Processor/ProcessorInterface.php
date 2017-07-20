<?php

namespace Zeeml\DataSet\Processor;

interface ProcessorInterface
{
    public function read(): array;
    
    public function write(): bool;
}
