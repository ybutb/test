<?php

declare(strict_types=1);

namespace App\Service\Sender;

use App\Model\Guest;
use stdClass;

// Better solution is to decouple senders from main app by message broker
class EmailSender implements SenderInterface
{
    public function __construct(private readonly StdClass $emailClient) // Assuming this is 3rd-party library
    {
    }

    public function send(Guest $guest): void
    {
        try {
//            $this->emailClient->send($guest->email);
        } catch (\Exception $e) {
            // log error
        }
    }
}
