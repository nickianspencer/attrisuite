<?php

namespace AttriSuite;

class DependentAttribute extends SelectAttribute {
    protected $dependencies = [];
    protected $parentAttributeName;

    /**
     * Set the name of the parent attribute.
     *
     * @param string $parentAttributeName
     */
    public function setParentAttributeName(string $parentAttributeName) {
        $this->parentAttributeName = $parentAttributeName;
    }

    /**
     * Get the name of the parent attribute.
     *
     * @return string
     */
    public function getParentAttributeName(): string {
        return $this->parentAttributeName;
    }

    /**
     * Add a dependency, specifying which attribute influences this one.
     *
     * @param mixed $parentValue The value of the parent attribute that triggers this dependency.
     * @param SelectAttribute $childAttribute The child attribute that is affected by the parent attribute's value.
     */
    public function addDependency($parentValue, SelectAttribute $childAttribute) {
        if (!isset($this->dependencies[$parentValue])) {
            $this->dependencies[$parentValue] = [];
        }
        $this->dependencies[$parentValue][] = $childAttribute;
    }

    /**
     * Validate the attribute based on its dependencies.
     *
     * @param mixed $parentValue The value of the parent attribute.
     * @param array $data The full data set being validated.
     * @return bool
     */
    public function validateWithDependencies($parentValue, $data): bool {
        if (!isset($this->dependencies[$parentValue])) {
            return false; // Return false if no dependencies are defined for this parent value
        }

        $selectedValue = $data[$this->getName()] ?? null;

        foreach ($this->dependencies[$parentValue] as $validOptionsAttribute) {
            if (in_array($selectedValue, $validOptionsAttribute->getOptions(), true)) {
                return true;
            }
        }

        return false; // No valid options matched
    }
}