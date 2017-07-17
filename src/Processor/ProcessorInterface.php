<?php

namespace Zeeml\Dataset\Processor;

interface ProcessorInterface
{
    public function read();
    
    public function write();
}
