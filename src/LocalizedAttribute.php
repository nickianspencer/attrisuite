<?php

namespace AttriSuite;

class LocalizedAttribute extends Attribute {
    protected $translations = array();

    public function addTranslation($locale, $name, $description) {
        $this->translations[$locale]= ['name' => $name, 'description' => $description];
    }

    public function getTranslation($locale) {
        return $this->translations[$locale] ?? ['name' => $this->name, 'description' => ''];
    }
}