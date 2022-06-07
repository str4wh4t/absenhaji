<?php
namespace Grahes\Validator;

use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Database\Capsule\Manager as Capsule;

class ValidatorFactory
{
    public $lang;
    public $group;
    public $factory;
    public $namespace;

    // Translations root directory
    public $basePath;

    public static $translator;

    public function __construct($namespace = 'lang', $lang = 'en', $group = 'validation')
    {
        $this->lang = $lang;
        $this->group = $group;
        $this->namespace = $namespace;
        $this->basePath = $this->getTranslationsRootPath();
        $this->factory = new Factory($this->loadTranslator());

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'port' => $_ENV['DB_PORT'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);

        $verifier = new DatabasePresenceVerifier($capsule->getDatabaseManager());
        $this->factory->setPresenceVerifier($verifier);
    }

    public function translationsRootPath(string $path = '')
    {
        if (!empty($path)) {
            $this->basePath = $path;
            $this->reloadValidatorFactory();
        }
        return $this;
    }

    private function reloadValidatorFactory()
    {
        $this->factory = new Factory($this->loadTranslator());
        return $this;
    }

    public function getTranslationsRootPath() : string
    {
        return dirname(__FILE__) . '/';
    }

    public function loadTranslator() : Translator
    {
        $loader = new FileLoader(new Filesystem(), $this->basePath . $this->namespace);
        $loader->addNamespace($this->namespace, $this->basePath . $this->namespace);
        $loader->load($this->lang, $this->group, $this->namespace);
        return static::$translator = new Translator($loader, $this->lang);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->factory, $method], $args);
    }
}