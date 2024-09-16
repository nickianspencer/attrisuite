<?php

namespace AttriSuite;

class AttributeRelationship {
    protected $type;
    protected $sourceAttribute;
    protected $targetAttributes = array();
    protected $rules = array();

    /**
     * Constructor for AttributeRelationship.
     * 
     * @param string $type
     * @param AttributeInterface $sourceAttribute
     */
    public function __construct($type, AttributeInterface $sourceAttribute) {
        $this->type = $type;
        $this->sourceAttribute = $sourceAttribute;
    }

    /**
     * Add a target attribute that is influenced by the source attribute.
     * 
     * @param AttributeInterface $targetAttribute
     */
    public function addTaretAttribute(AttributeInterface $targetAttribute) {
        $this->targetAttributes[] = $targetAttribute;
    }

    /**
     * Add a rule that defines the relationship between the source and target attributes.
     * 
     * @param callable $rule
     */
    public function addRule(callable $rule) {
        $this->rules[] = $rule;
    }

    /**
     * Validate the relationship based on the defined rules.
     * 
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool {
        foreach($this->rules as $rule) {
            if (!$rule($this->sourceAttribute, $this->targetAttributes, $data)) {
                return false;
            }
        }
        return true;
    }
}