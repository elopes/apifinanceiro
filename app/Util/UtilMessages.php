<?php

namespace App\Util;

class UtilMessages
{
    private $message = [];

    public function __construct(string $message, array $data = [])
    {
        $this->message['message'] = $message;
        $this->message['errors']  = $data;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
