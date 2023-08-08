<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Guest;
use App\Repository\Repository;

class SessionService
{
    public const CSRF_KEY = 'csrf';
    public const GUEST_KEY = 'guest';

    public function __construct(private readonly Repository $repository)
    {
    }

    public function generateCsrf(): string
    {
        return $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
    }

    public function validateCsrf(?string $csrf): bool
    {
        return $csrf || isset($_SESSION[self::CSRF_KEY]) || $_SESSION[self::CSRF_KEY] === $csrf;
    }

    public function getGuest(): Guest
    {
        $guestId = $_SESSION[self::GUEST_KEY] ?? null;

        if (!$guestId) {
            $guest = $this->repository->createGuest();
            $_SESSION[self::GUEST_KEY] = $guest->id;

            return $guest;
        }

        $guest = $this->repository->getGuestById($guestId);

        if (!$guest) {
            $guest = $this->repository->createGuest();
            $_SESSION[self::GUEST_KEY] = $guest->id;
        }

        return $guest;
    }
}