<?php
namespace VideoCloud\Http;

final class Error
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function code()
    {
        return $this->response->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function message()
    {
        return $this->response->error;
    }
}
