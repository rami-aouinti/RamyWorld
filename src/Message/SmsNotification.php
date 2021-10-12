<?php

namespace App\Message;

/**
 * Class SmsNotification
 */
class SmsNotification
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent():string
    {
        return $this->content;
    }
}
