<?php

namespace AttriSuite;

class PluginManager {
    protected $plugins = array();

    public function registerPlugin($plugin) {
        $this->plugins[] = $plugin;
    }

    public function executePlugins() {
        foreach ($this->plugins as $plugin) {
            $plugin->execute();
        }
    }
}