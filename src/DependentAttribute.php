<?php

namespace AttriSuite;

class DependentAttribute extends SelectAttribute {
    protected $dependencies = array();

    public function addDependency($parentValue, SelectAttribute $childAttribute) {
        $this->dependencies[$parentValue] = $childAttribute;
    }

    public function getDependency($parentValue): ?SelectAttribute {
        return $this->dependencies[$parentValue] ?? null;
    }

    public function validate($value): bool {
        return parent::validate($value);
    }
}