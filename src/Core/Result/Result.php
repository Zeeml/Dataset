<?php

namespace Zeeml\DataSet\Core\Result;

abstract class Result implements ResultInterface
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}