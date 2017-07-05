<?php

namespace Slim\Views;

use Psr\Http\Message\ResponseInterface;
use League\Plates\Engine;
use ArrayAccess;
use Countable;
use IteratorAggregate;
use ArrayIterator;

class Plates implements ArrayAccess, Countable, IteratorAggregate {

    /**
     * Instance of a Plates engine
     * 
     * @var Engine
     */
    protected $engine;

    /**
     * Default view attributes
     * 
     * @var array
     */
    protected $attributes;

    /**
     * Create new Plates view
     * 
     * @param string $templatePath
     * @param array $attributes
     */
    public function __construct($templatePath = "", $attributes = [])
    {
        $this->engine = new Engine($templatePath);
        $this->attributes = $attributes;
    }

    /**
     * Retrive Plates engine instance
     * 
     * @return Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Render a template
     * 
     * @param ResponseInterface $response
     * @param string $template
     * @param array $data
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $data = [])
    {
        $output = $this->fetch($template, $data);

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * Get the attributes for the renderer
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the attributes for the renderer
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Set an attribute
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Retrieve an attribute
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return null;
        }

        return $this->attributes[$key];
    }

    /**
     * Renders a template and returns the result as a string
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    public function fetch($template, array $data = [])
    {
        $attributes = array_merge($this->attributes, $data);

        return $this->engine->render($template, $attributes);
    }

    /**
     * Whether an attribute exists
     *
     * @param $key
     * @param $value
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Retrieve an attribute
     *
     * @param $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Set an attribute
     *
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        return $this->attributes[$key] = $value;
    }

    /**
     * Unset an attribute
     *
     * @param $key
     */
    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Retrive number of attributes
     * 
     * @return type
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * Retrieve an attributes iterator
     * 
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }

}
