<?php

namespace AttriSuite;

class AttributeManager {
    protected $attributes = array();
    protected $attributeSets = array();
    protected $relationships = array();

    /**
     * Add an attribute to the manager.
     * 
     * @param AttributeInterface $attribute
     */
    public function addAttribute(AttributeInterface $attribute) {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Add an attribute set to the manager.
     * 
     * @param AttributeSet $attributeSet
     */
    public function addAttributeSet(AttributeSet $attributeSet) {
        $this->attributes[$attributeSet->getName()] = $attributeSet;
    }

    /**
     * Get an attribute by name.
     * 
     * @param string $name
     * @return AttributeInterface|null
     */
    public function getAttribute($name): ?AttributeInterface {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Get an attribute set by name.
     * 
     * @param string $name
     * @return AttributeSet|null
     */
    public function getAttributeSet($name): ?AttributeSet {
        return $this->attributeSets[$name] ?? null;
    }

    /**
     * Add an attribute relationship to the manager
     * 
     * @param AttributeRelationship $relationship
     */
    public function addRelationship(AttributeRelationship $relationship) {
        $this->relationships[] = $relationship;
    }

    /**
     * Validate a set of attributes against the provided data.
     * 
     * @param array $data
     * @return bool
     */
    public function validateAttributes($data): bool {
        // Validate individual attributes
        foreach ($this->attributes as $attribute) {
            $name = $attribute->getName();
            if (isset($data[$name]) && !$attribute->validate($data[$name])) {
                return false;
            }
        }

        //Validate attribute relationships
        foreach($this->relationships as $relationship) {
            if (!$relationship->validate($data)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Validate an attribute set against the provided data.
     * 
     * @param string $setName
     * @param array $data
     * @return bool
     */
    public function validateAttributesSet($setName, $data): bool {
        $attributeSet = $this->getAttributeSet($setName);
        if($attributeSet) {
            return $attributeSet->validateAttributes($data);
        }
        return false;
    }
}