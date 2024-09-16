<?php

namespace AttriSuite;

class Attribute implements AttributeInterface {
    protected $name;
    protected $type;
    protected $value;
    protected $validationRules = [];

    /**
     * Constructor for the Attribute Class.
     * 
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get the name of the attribute.
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get the type of the attribute.
     * 
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Get the current value of the attribute.
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set the value of the attribute.
     * 
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Validate the value of the attribute based on its type.
     * 
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool {
        // Implement validation logic based on type and custom rules
        foreach ($this->validationRules as $rule) {
            if (!$rule($value)) {
                return false;
            }
        }
        return true;
    }

        /**
     * Add a custom validation rule.
     *
     * @param callable $rule
     */
    public function addValidationRule(callable $rule) {
        $this->validationRules[] = $rule;
    }
}
