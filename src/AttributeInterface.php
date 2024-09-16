<?php

namespace AttriSuite;

interface AttributeInterface {
    /**
     * Get the name of the attribute.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the type of the attribute.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Validate the value of the attribute.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool;
}