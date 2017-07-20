<?php

namespace Zeeml\DataSet\Processor;

use League\Csv\Reader;
use Zeeml\DataSet\Exception\FileNotFoundException;

class CsvProcessor extends AbstractProcessor
{
    public function __construct(string $source)
    {
        parent::__construct($source);
    }

    public function read(): array
    {
        if ( isset($this->data)) {
            return $this->data;
        }

        if (!is_file($this->source)) {
            throw new FileNotFoundException('File ' . $this->source . ' was not found');
        }

        $reader = Reader::createFromPath($this->source);
        $reader->stripBom(true);
        $reader->setOffset(1);
        $this->data = [];
        $reader->each(function($line) {
            $this->data[] = $line;

            return true;
        });

        return $this->data;
    }
    
    public function write(): bool
    {
        return true;
    }
}

