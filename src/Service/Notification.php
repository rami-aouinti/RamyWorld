<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\NotificationRepository;

class Notification
{

    private NotificationRepository $repository;

    /**
     * @param NotificationRepository $repository
     */
    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return \App\Entity\Notification[]
     */
    public function collection(): array
    {
        $unreadNotification = $this->repository->findBy([
            'readed' => 0
        ]);
        return [
            'notification_unread' => count($unreadNotification),
            'notifications' => $this->repository->findAll()
        ];
    }
}
