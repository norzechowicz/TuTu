<?php

namespace Coduo\TuTu\Config\Element;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Request
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $allowedMethods;

    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->validatePath($path);
        $this->path = $path;
        $this->allowedMethods = [];
        $this->request = [];
        $this->query = [];
        $this->headers = [];
        $this->body = '';
    }

    public static function fromArray(array $arrayConfig)
    {
        $configResolver = self::createArrayConfigResolver();
        $config = $configResolver->resolve($arrayConfig);
        $responseConfig = new Request($config['path']);

        $responseConfig->setAllowedMethods($config['methods']);
        $responseConfig->setBodyParameters($config['request']);
        $responseConfig->setQueryParameters($config['query']);
        $responseConfig->setHeaders($config['headers']);
        $responseConfig->setBody($config['body']);

        return $responseConfig;
    }

    /**
     * @param $methods
     */
    public function setAllowedMethods($methods)
    {
        $this->allowedMethods = array_map(function ($method) {
            return strtoupper($method);
        }, $methods);
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return '/' . ltrim($this->path, '/');
    }

    /**
     * @return bool
     */
    public function hasBodyParameters()
    {
        return (boolean) count($this->request);
    }

    /**
     * @param $parameters
     */
    public function setBodyParameters($parameters)
    {
        $this->request = $parameters;
    }

    /**
     * @return array
     */
    public function getBodyParameters()
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function hasQueryParameters()
    {
        return (boolean) count($this->query);
    }

    /**
     * @param $parameters
     */
    public function setQueryParameters($parameters)
    {
        $this->query = $parameters;
    }

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->query;
    }

    /**
     * @return boolean
     */
    public function hasHeaders()
    {
        return (boolean) count($this->headers);
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return !empty($this->body);
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $path
     * @throws \InvalidArgumentException
     */
    private function validatePath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException("Path must be a valid string.");
        }

        if (empty($path)) {
            throw new \InvalidArgumentException("Path can't be empty.");
        }
    }

    /**
     * @return OptionsResolver
     */
    private static function createArrayConfigResolver()
    {
        $configResolver = new OptionsResolver();
        $configResolver->setRequired(['path']);
        $configResolver->setDefaults([
            'methods' => [],
            'request' => [],
            'query' => [],
            'headers' => [],
            'body' => ''
        ]);
        $configResolver->setAllowedTypes('path', 'string');
        $configResolver->setAllowedTypes('methods', 'array');
        $configResolver->setAllowedTypes('request', 'array');
        $configResolver->setAllowedTypes('query', 'array');
        $configResolver->setAllowedTypes('headers', 'array');
        $configResolver->setAllowedTypes('body', 'string');

        return $configResolver;
    }
}
