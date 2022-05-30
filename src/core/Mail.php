<?php

namespace Core;

class Mail
{
    public static function send(
        string $to,
        string $subject,
        string $message,
        array $header = []
    ): bool {
        return mail($to, $subject, $message, $header);
    }
}
