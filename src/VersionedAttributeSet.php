<?php

namespace AttriSuite;

class VersionedAttributeSet extends AttributeSet {
    protected $versions = array();

    public function saveVersion() {
        $this->versions[] = clone $this;
    }

    public function getVersion($versionNumber) {
        return $this->versions[$versionNumber] ?? null;
    }
}