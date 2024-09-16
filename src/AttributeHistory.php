<?php

namespace AttriSuite;

class AttributeHistory {
    protected $changes = array();

    /**
     * Log a change to an attribute
     * 
     * @param string $attributeName The name of the attribute that was changed.
     * @param mixed $oldValue The old value of the attribute.
     * @param mixed $newValue The new value of the attribute.
     * @param string $changedBy The user who made the change.
     * @param \DateTime $changedAt The time the change was made.
     */
    public function logChange($attributeName, $oldValue, $newValue, $changedBy, \DateTime $changedAt) {
        $this->changes[] = [
            "attribute"=> $attributeName,
            "old_value"=> $oldValue,
            "new_value"=> $newValue,
            "changed_by"=> $changedBy,
            "changed_at"=> $changedAt
        ];
    }

    /**
     * Get the history of changes for a specific attribute.
     * 
     * @param string $attributeName
     * @return array
     */
    public function getHistory($attributeName): array {
        return array_filter($this->changes, function($change) use ($attributeName) {
            return $change["attribute"] === $attributeName;
        });
    }

    /**
     * Get the full history of changes
     * 
     * @return array
     */
    public function getFullHistory(): array {
        return $this->changes;
    }
}