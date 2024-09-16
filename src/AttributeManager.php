<?php

namespace AttriSuite;

class AttributeManager {
    protected $attributes = array();
    protected $attributeSets = array();
    protected $attributeProfiles = array();
    protected $relationships = array();
    protected $history;

    public function __construct() {
        $this->history = new AttributeHistory();
    }

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
        $this->attributeSets[$attributeSet->getName()] = $attributeSet;
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
    public function validateAttributes(array $data): bool {
        foreach ($this->attributes as $attribute) {
            $name = $attribute->getName();

            if ($attribute instanceof DependentAttribute) {
                // Identify the correct parent value
                $parentAttributeName = $attribute->getParentAttributeName();
                $parentValue = $data[$parentAttributeName] ?? null;

                // Validate the attribute with the parent value
                if (!$attribute->validateWithDependencies($parentValue, $data)) {
                    return false;
                }
            } else {
                if (isset($data[$name]) && !$attribute->validate($data[$name])) {
                    return false;
                }
            }
        }

        // Validate relationships between attributes
        foreach ($this->relationships as $relationship) {
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
    public function validateAttributeSet($setName, $data): bool {
        $attributeSet = $this->getAttributeSet($setName);
        if($attributeSet) {
            return $attributeSet->validateAttributes($data);
        }
        return false;
    }

    /**
     * Add an attribute profile to the manager.
     * 
     * @param AttributeProfile $profile
     */
    public function addProfile(AttributeProfile $profile): bool {
        if (isset($this->attributeProfiles[$profile->getName()])) {
            // Log the overwrite or issue a warning
            throw new \Exception("Profile with the name '{$profile->getName()}' already exists.");
        }
        $this->attributeProfiles[$profile->getName()] = $profile;
        return true;
    }

    /**
     * Get an attribute profile by name.
     * 
     * @param string $name
     * @return AttributeProfile|null
     */
    public function getProfile(string $name): ?AttributeProfile {
        return $this->attributeProfiles[$name] ?? null;
    }

    /**
     * Validate attributes against a specific profile.
     * 
     * @param string $profileName
     * @param array $data
     * @return bool
     */
    public function validateProfile(string $profileName, array $data): bool {
        $profile = $this->getProfile($profileName);
        if ($profile) {
            return $profile->validate($data);
        }
        return false;
    }

    /**
     * Update an attribute and log the change.
     * 
     * @param string $attributeName
     * @param mixed $newValue
     * @param string $changedBy
     * @return bool
     */
    public function updateAttribute($attributeName, $newValue, $changedBy): bool {
        if (isset($this->attributes[$attributeName])) {
            $oldValue = $this->attributes[$attributeName]->getValue();
            $this->attributes[$attributeName]->setValue($newValue);
            $this->history->logChange($attributeName, $oldValue, $newValue, $changedBy, new \DateTime());
            return true;
        }
        return false;
    }

    /**
     * Get the history of changes for a specific attribute.
     * 
     * @param string $attributeName
     * @return array
     */
    public function getAttributeHistory($attributeName): array {
        return $this->history->getHistory($attributeName);
    }

    /**
     * Get the full history of all attribute changes.
     * 
     * @return array
     */
    public function getFullHistory(): array {
        return $this->history->getFullHistory();
    }
}