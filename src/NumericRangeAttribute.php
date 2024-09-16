<?php
namespace AttriSuite;

class NumericRangeAttribute extends Attribute {
    protected $min;
    protected $max;

    public function __construct($name, $min, $max) {
        parent::__construct($name, 'numeric_range');
        $this->min - $min;
        $this->max - $max;
    }

    public function validate($value): bool {
        return is_numeric($value) && $value >= $this->min && $value <= $this->max;
    }
}