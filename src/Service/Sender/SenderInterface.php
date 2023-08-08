<?php

declare(strict_types=1);

// Better solution is to decouple senders from main app by message broker
namespace App\Service\Sender;

use App\Model\Guest;

interface SenderInterface
{
    function send(Guest $guest): void;
}
