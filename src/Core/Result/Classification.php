<?php

namespace Zeeml\DataSet\Core\Result;

class Classification extends Result
{
    protected $confidence;
    protected $probabilities;

    public function __construct($value, float $confidence)
    {
        parent::__construct($value);
        $this->confidence = $confidence;
        $this->probabilities = [];
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /**
     * @return array
     */
    public function getProbabilities(): array
    {
        return $this->probabilities;
    }

    /**
     * @return array
     */
    public function getProbability($output)
    {
        return $this->probabilities[$output] ?? null;
    }

    /**
     * @param $output
     * @param $probability
     */
    public function addProbability($output, $probability)
    {
        $this->probabilities[$output] = $probability;
    }
}
