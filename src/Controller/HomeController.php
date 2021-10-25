<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Util\MonologDBHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param MailerInterface $mailer
     * @param Request $request
     * @return Response
     */
    #[Route('/home', name: 'home')]
    public function index(MailerInterface $mailer, Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    #[Route('/notification', name: 'notification')]
    public function notification(NotificationRepository $repository): Response
    {
        return $this->render('home/notification.html.twig', [
            'notifications' => $repository->findBy([
                'user' => $this->security->getUser()
            ]),
        ]);
    }
}
