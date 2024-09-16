<?php
namespace AttriSuite;

class FileAttribute extends Attribute {
    protected $allowedTypes;
    protected $maxSize;

    public function __construct($name, array $allowedTypes = null, $maxSize = null) {
        parent::__construct($name, 'file');
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
    }

    public function validate($value): bool {
        return in_array($value['type'], $this->allowedTypes) && $value['size'] <= $this->maxSize;
    }
}

