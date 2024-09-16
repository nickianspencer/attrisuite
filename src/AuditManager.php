<?php

namespace AttriSuite;

class AuditManager {
    protected $logs = array();

    public function logChange($attributeName, $oldValue, $newValue) {
        $this->logs[] = [
            'attribute' => $attributeName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'timestamp' => time()
        ];
    }

    public function getLogs() {
        return $this->logs;
    }
}