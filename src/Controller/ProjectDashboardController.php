<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectDashboardController extends AbstractController
{
    #[Route('/projectDashboard/dashboard', name: 'project_dashboard')]
    public function index(): Response
    {
        return $this->render('project_dashboard/index.html.twig', [
            'controller_name' => 'ProjectDashboardController',
        ]);
    }
}
