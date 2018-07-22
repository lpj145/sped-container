<?php
namespace SpedTransform;

class SpedData implements \Iterator
{
    /**
     * @var array
     */
    private $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ?
            $this->data[$key] :
            $default;
    }

    public function overwrite(array $data)
    {
        $this->data = $data;
    }

    public function insert($key, $data)
    {
        if ($this->has($key)) {
            $this->data[$key] += $data;
            return $this;
        }

        $this->data[$key] = $data;
        return $this;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
        return $this;
    }

    /**
     * @return \stdClass
     */
    public function toStd(): \stdClass
    {
        return (object)$this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    public function current()
    {
        return current($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid()
    {
        $key = $this->key();
        return null !== $key && false !== $key;
    }

    public function rewind()
    {
        reset($this->data);
    }

}
