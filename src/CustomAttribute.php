<?php

namespace AttriSuite;

class CustomAttribute extends Attribute {
    protected $validationRules = array();

    public function addValidationRules(callable $rule) {
        $this->validationRules[] = $rule;
    }

    public function validate($value): bool {
        foreach ($this->validationRules as $rule) {
            if (!$rule($value)) {
                return false;
            }
        }
        return true;
    }
}