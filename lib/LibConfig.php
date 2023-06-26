<?php

namespace Jarvis\lib;

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class LibConfig {
    /**
     * The path to the configuration file.
     *
     * @var string
     */
    protected $file;

    /**
     * The configuration data.
     *
     * @var array
     */
    protected $data;

    /**
     * LibConfig constructor.
     *
     * @param string $file The path to the configuration file.
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->data = $this->read();
    }

    /**
     * Get a value from the configuration data.
     *
     * @param string $key     The key to retrieve.
     * @param mixed  $default The default value to return if the key does not exist.
     *
     * @return mixed The value associated with the key, or the default value if the key does not exist.
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Set a value in the configuration data.
     *
     * @param string $key   The key to set.
     * @param mixed  $value The value to set.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        $this->save();
    }

    /**
     * Read the configuration file.
     *
     * @return array The parsed configuration data.
     */
    protected function read()
    {
        if (file_exists($this->file)) {
            return Yaml::parseFile($this->file);
        }
        return [];
    }

    /**
     * Save the configuration data to the file.
     *
     * @return void
     */
    public function save()
    {
        $content = Yaml::dump($this->data);
        file_put_contents($this->file, $content);
    }
}
