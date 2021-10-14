<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Entity\Education;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Experience;
use App\Entity\File;
use App\Entity\Hobby;
use App\Entity\Language;
use App\Entity\Log;
use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\Profile;
use App\Entity\Project;
use App\Entity\Resume;
use App\Entity\Skill;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\Ticket;
use App\Entity\User;
use App\Entity\Work;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;



class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="logo.jpg">')
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
            ->disableUrlSignatures()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Web', 'fab fa-google', 'home');
        yield MenuItem::subMenu('Users Management', 'fa fa-users')->setSubItems([
            MenuItem::linkToCrud('Users', 'fa fa-user-circle', User::class),
            MenuItem::linkToCrud('Profile', 'fa fa-user-md', Profile::class),
            MenuItem::linkToCrud('Department', 'fas fa-building', Department::class),
        ]);
        yield MenuItem::subMenu('CV Management', 'fa fa-caret-square-o-up')->setSubItems([
            MenuItem::linkToCrud('Education', 'fas fa-graduation-cap', Education::class),
            MenuItem::linkToCrud('Experience', 'fas fa-flask', Experience::class),
            MenuItem::linkToCrud('Hobby', 'fas fa-list', Hobby::class),
            MenuItem::linkToCrud('Language', 'fas fa-tasks', Language::class),
            MenuItem::linkToCrud('Project', 'fa fa-product-hunt', Project::class),
            MenuItem::linkToCrud('Resume', 'fa fa-file-pdf-o', Resume::class),
            MenuItem::linkToCrud('Skill', 'fas fa-asterisk', Skill::class),
            MenuItem::linkToCrud('Projects', 'fas fa-briefcase', Work::class)
        ]);

        yield MenuItem::subMenu('Project Management', 'fa fa-calendar')->setSubItems([
            MenuItem::linkToCrud('Project', 'fas fa-list', Project::class),
            MenuItem::linkToCrud('Task', 'fas fa-list', Task::class),
            MenuItem::linkToCrud('Ticket', 'fas fa-list', Ticket::class),
            MenuItem::linkToCrud('Status', 'fas fa-list', Status::class)
        ]);

        yield MenuItem::subMenu('Events Management', 'fa fa-calendar')->setSubItems([
            MenuItem::linkToCrud('Event', 'fas fa-list', Event::class),
            MenuItem::linkToCrud('EventType', 'fas fa-list', EventType::class),
            MenuItem::section('Logs Management'),
            MenuItem::linkToCrud('Log', 'fas fa-list', Log::class),
            MenuItem::linkToCrud('Message', 'fas fa-list', Message::class),
            MenuItem::linkToCrud('Notification', 'fas fa-list', Notification::class),
        ]);

        yield MenuItem::subMenu('Others Management', 'fa fa-folder')->setSubItems([
            MenuItem::linkToCrud('Tag', 'fa fa-tags', Tag::class),
            MenuItem::linkToCrud('File', 'fa fa-file-text', File::class)
        ]);

        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('dashboard/assets/css/material-dashboard.css')
            ->addCssFile('dashboard/assets/css/nucleo-icons.css')
            ->addCssFile('dashboard/assets/css/nucleo-svg.css');
    }
}
