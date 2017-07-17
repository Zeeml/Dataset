<?php

namespace Zeeml\Dataset\Processor;

use League\Csv\Reader;

class CsvProcessor extends AbstractProcessor
{
    public function read()
    {
        if ($this->uri) {
            $reader = Reader::createFromPath($this->uri);
            $reader->stripBom(true);
            $reader->setOffset(1);
            $this->data = [];
            $reader->each(function($line) {
                $this->data[] = $line;
                return true;
            });
        }
    
        return $this;
    }
    
    public function write()
    {
        return true;
    }
}

