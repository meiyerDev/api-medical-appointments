<?php

namespace App\Libs;

class Response
{
    private $data;

    function __construct($data = [], int $code = 200)
    {
        $this->data = $data + [
            'status' => $code
        ];
    }

    public function toJson()
    {
        header('Content-Type: application/json');
        return json_encode($this->data);
    }
}
