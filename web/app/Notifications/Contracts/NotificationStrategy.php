<?php
namespace App\Notifications\Contracts;

interface NotificationStrategy
{
    public function send(string $to, string $subjectOrMessage, string $body = null): bool;
}



