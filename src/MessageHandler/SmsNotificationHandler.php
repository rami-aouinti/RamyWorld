<?php

namespace App\MessageHandler;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class SmsNotificationHandler
 */
class SmsNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(SmsNotification $message)
    {

    }
}
