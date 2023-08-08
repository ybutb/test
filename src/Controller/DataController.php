<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Data;
use App\Repository\Repository;
use App\Service\Sender\SenderInterface;
use App\Service\SessionService;

class DataController
{
    private array $senders;

    public function __construct(
        private readonly Repository $repository,
        private readonly SessionService $sessionService,
        SenderInterface ...$senders
    )
    {
        $this->senders = $senders;
    }

    public function index(): void
    {
        $guest = $this->sessionService->getGuest();
        $data = $this->repository->getLastDataByGuestId($guest->id);
        $csrf = $this->sessionService->generateCsrf();

        $renderData = [
            'data' => $data?->data ? $this->sanitizeOutput($data->data) : '',
            'csrf' => $csrf,
        ];

        $this->render($renderData);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error(405);
        }

        $csrfValid = $this->sessionService->validateCsrf($_POST['csrf']);

        if (!$csrfValid) {
            $this->error(403);
        }

        $guest = $this->sessionService->getGuest();
        $data = $_POST['data'] ?? null;

        $dataModel = new Data();
        $dataModel->data = $data;
        $dataModel->guestId = $guest->id;

        $this->repository->saveData($dataModel);

        foreach ($this->senders as $sender) {
            $sender->send($guest);
        }

        $this->redirect('/');
    }

    private function sanitizeOutput($data): string
    {
        return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401, 'UTF-8');
    }

    private function render(array $data): void
    {
        ob_start();

        extract($data);
        include __DIR__ . '/../template/form.html';
        $content = ob_get_clean();

        http_response_code(200);

        echo $content;
    }

    private function error(int $code): void
    {
        http_response_code($code);
        exit;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url, true, 303);
        die();
    }
}
