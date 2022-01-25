<?php

namespace App\Libs;

class Request
{
    private $data;
    private $headers;

    function __construct(array $data = [])
    {
        if (empty($data)) {
            $this->data = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST : $_GET;
        } else {
            $this->data = $data;
        }

        $this->headers = getallheaders();
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function hasAll(array $keys)
    {
        foreach ($keys as $key) {
            if ($this->has($key)) continue;
            return false;
        }
        return true;
    }

    public function missingAny(array $keys)
    {
        foreach ($keys as $key) {
            if ($this->has($key)) continue;
            return true;
        }
        return false;
    }

    public function hasHeader($key)
    {
        return isset($this->headers[$key]);
    }

    public function getHeader(string $key)
    {
        return $this->hasHeader($key) ? $this->headers[$key] : null;
    }

    public function addUser(array $data)
    {
        $this->data['user'] = $data;
    }
}
