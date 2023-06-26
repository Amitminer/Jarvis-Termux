<?php

namespace Jarvis\lib;

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class LibConfig {
    
    protected $file;
    protected $data;

    public function __construct($file)
    {
        $this->file = $file;
        $this->data = $this->read();
    }

    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        $this->save();
    }

    protected function read()
    {
        if (file_exists($this->file)) {
            return Yaml::parseFile($this->file);
        }
        return [];
    }

    public function save()
    {
        $content = Yaml::dump($this->data);
        file_put_contents($this->file, $content);
    }
}
