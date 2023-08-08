<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Data;
use App\Model\Guest;
use PDO;

class Repository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getLastDataByGuestId(int $id): ?Data
    {
        $query = $this->pdo->prepare('SELECT * FROM data WHERE guest_id = :id ORDER BY id DESC LIMIT 1');
        $query->execute(['id' => $id]);
        $result = $query->fetch();

        if (!$result) {
            return null;
        }

        $model = new Data();
        $model->data = $result['data'];
        $model->guestId = $result['guest_id'];

        return $model;
    }

    public function getGuestById(int $id): ?Guest
    {
        $query = $this->pdo->prepare('SELECT * FROM guest WHERE id = :id');

        $query->execute(['id' => $id]);
        $result = $query->fetch();

        if (!$result) {
            return null;
        }

        $model = new Guest();
        $model->id = $result['id'];
        $model->email = $result['email'];
        $model->phone = (int) $result['phone'];

        return $model;
    }

    public function createGuest(): Guest
    {
        $model = new Guest();
        $model->email = 'test@email.com'; // Assuming email was added from the request.
        $model->phone = 1234567889; // Assuming phone was added from the request.

        $query = $this->pdo->prepare('INSERT INTO guest (email, phone) VALUES (:email, :phone)');
        $query->execute(['email' => $model->email, 'phone' => $model->phone]);

        $model->id = (int) $this->pdo->lastInsertId();

        return $model;
    }

    public function saveData(Data $data): void
    {
        $query = $this->pdo->prepare('INSERT INTO data (data, guest_id) VALUES (:data, :guestId)');

        $query->execute([
            'data' => $data->data,
            'guestId' => $data->guestId,
        ]);

        $data->id = (int) $this->pdo->lastInsertId();
    }
}