<?php

namespace Zeeml\DataSet\DataSet\Result;

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