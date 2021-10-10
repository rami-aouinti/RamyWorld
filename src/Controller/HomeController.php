<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Util\MonologDBHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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

    #[Route('/home', name: 'home')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('ramideveloper5@gmail.com')
            ->to('rami.aouinti@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        //$logger = new Logger('doctrine');
        //$logger->info('Information message');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
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
