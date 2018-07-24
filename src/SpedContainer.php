<?php
namespace SpedTransform;

use Adbar\Dot;
use SpedTransform\Support\SpedAttribute;

class SpedContainer
{
    const CACHE_CONFIG_NAME = 'spedContainerCache';
    const DATA_DEPENDENCY_NAME = '_data';

    /**
     * @var array
     */
    private $configs = [];
    /**
     * @var array
     */
    private $objects = [];
    /**
     * @var Dot
     */
    private $items;

    public function __construct(array $class, array $items)
    {
        $this->items = new Dot($items);

        if (false === isset($class[self::CACHE_CONFIG_NAME])) {
           $class = $this->setUp($class);
        }

        unset($class[self::CACHE_CONFIG_NAME]);
        $this->configs = $class;
    }

    /**
     * @return array
     */
    public function getConfigCache()
    {
        return array_merge($this->configs, [self::CACHE_CONFIG_NAME => true]);
    }

    public function execute()
    {
        foreach ($this->configs as $configName => $config) {
            $this->executeDependencies($configName);
        }
    }

    /**
     * @param null $key
     * @return array
     */
    public function getAll()
    {
        return $this->objects;
    }

    /**
     * @param $dependencyName
     */
    protected function executeDependencies($dependencyName)
    {
        $dependencies = $this->getDependecy($dependencyName);

        if (null === $dependencies) {
            $this->executeAttributeMacros(
                $this->getObject($dependencyName),
                $this->getDependencyData($dependencyName)
            );
            return;
        }

        $objects = [];
        foreach ($dependencies as $index => $dependency) {
            if (self::DATA_DEPENDENCY_NAME === $index) {
                continue;
            }

            $objects[] = $this->getObject($dependency);
            $this->executeDependencies($dependency);
        }

        $this->executeAttributeMacros(
            $this->getObject($dependencyName),
            $this->getDependencyData($dependencyName),
            ...$objects
        );
    }

    /**
     * @param SpedAttribute $spedAttribute
     * @param mixed ...$arguments
     * @return SpedAttribute
     */
    protected function executeAttributeMacros(SpedAttribute $spedAttribute, ...$arguments): SpedAttribute
    {
        if ($spedAttribute->isExecuted()) {
            return $spedAttribute;
        }

        // Remove when items cannot passed
        if (false === $arguments[0]) {
            unset($arguments[0]);
        }

        $spedAttribute(...$arguments);
        return $spedAttribute;
    }

    /**
     * Set up attributes and class params
     * @param $classes
     * @return array
     * @throws \ReflectionException
     */
    private function setUp($classes): array
    {
        $configs = [];
        foreach ($classes as $className) {
            $reflectClass = new \ReflectionClass($className);
            $configs = array_merge(
                $this->setClassToConfig(
                    $reflectClass->getName(),
                    $reflectClass->getShortName(),
                    $reflectClass->getMethod('__invoke')->getParameters()
                ),
                $configs
            );
        }

        return $configs;
    }

    /**
     * @param $className
     * @param \ReflectionParameter[] $classParams
     * @return array
     */
    private function setClassToConfig($className, $classShortName, array $classParams)
    {
        if (count($classParams) === 0) {
            return [$className => null];
        }

        $classes = [];
        foreach ($classParams as $classParam) {
            if ($classParam->isArray()) {
                $classes[$className][self::DATA_DEPENDENCY_NAME] = strtolower(str_replace('Attribute', '', $classShortName));
                continue;
            }

            if (null === $classParam->getType()) {
                $classes[$className] = null;
                continue;
            }

            $classes[$className][] = $classParam->getType()->getName();
        }

        return $classes;
    }

    /**
     * @param $className
     * @return mixed
     */
    private function getItems($className)
    {
        return $this->items->get($className);
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getObject($name)
    {
        if (isset($this->objects[$name])) {
            return $this->objects[$name];
        }

        return $this->objects[$name] = new $name;
    }

    /**
     * @param $dependencyName
     * @return mixed
     */
    private function getDependecy($dependencyName)
    {
        if (!array_key_exists($dependencyName, $this->configs)) {
            throw new \RuntimeException('Depedency '.$dependencyName.' cannot found on configs!');
        }

        return $this->configs[$dependencyName];
    }

    /**
     * @param $dependencyName
     * @return mixed
     */
    private function getDependencyData($dependencyName)
    {
        if (isset($this->configs[$dependencyName][self::DATA_DEPENDENCY_NAME])) {
            return $this->getItems($this->configs[$dependencyName][self::DATA_DEPENDENCY_NAME]);
        }
        return false;
    }
}
