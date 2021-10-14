<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Ticket;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\TicketRepository;
use App\Service\PDFService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/task')]
class TaskController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request, TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->list($paginator, $request, $this->security->getUser());
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/searchDate/{date}', name: 'task_search_date', methods: ['GET'])]
    public function searchDate(PaginatorInterface $paginator, Request $request, TaskRepository $taskRepository, $date)
    {
        if ($date == 'month') {
            $tasks = $taskRepository->lastMonth($paginator, $request, $this->security->getUser());
        } elseif ($date == 'week') {
            $tasks = $taskRepository->lastWeek($paginator, $request, $this->security->getUser());
        }
        else {
            $tasks = $taskRepository->lastDay($paginator, $request, $this->security->getUser());
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/new/task", name="api_task_new_task", methods={"POST"})
     */
    public function newTask(Request $request, TicketRepository $ticketRepository): Response
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(isset($donnees->description) && !empty($donnees->description))
        {
            $task = new Task();
            $task->setUser($this->security->getUser());
            $code = 201;
            /** @var Ticket $ticket */
            $ticket = $ticketRepository->findOneBy([
                'name' => $donnees->ticket
            ]);
            // On hydrate l'objet avec les données
            $task->setDescription($donnees->description);
            $task->setStartDate(new DateTime($donnees->startDate));
            $task->setEndDate(new DateTime($donnees->endDate));
            $task->setTicket($ticket);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            // On retourne le code
            return new JsonResponse([
                'status' => $code,
                'data' => $task
            ]);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    }

    /**
     * @Route("/{id}/edit/task", name="api_task_edit_task", methods={"PUT"})
     */
    public function editTask(Request $request, TaskRepository $taskRepository, TicketRepository $ticketRepository, $id): Response
    {
        $donnees = json_decode($request->getContent());

        if(isset($donnees->description) && !empty($donnees->description))
        {
            $task = $taskRepository->find($id);
            $code = 201;
            /** @var Ticket $ticket */
            $ticket = $ticketRepository->findOneBy([
                'name' => $donnees->ticket
            ]);
            // On hydrate l'objet avec les données
            $task->setDescription($donnees->description);
            $task->setStartDate(new DateTime($donnees->startDate));
            $task->setEndDate(new DateTime($donnees->endDate));
            $task->setTicket($ticket);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            // On retourne le code
            return new JsonResponse([
                'status' => $code,
                'data' => $task
            ]);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    }

    #[Route('/export', name: 'task_export', methods: ['GET'])]
    public function export(TaskRepository $taskRepository, PDFService $pdfService)
    {
        $tasks = $taskRepository->findBy([
            'user' => $this->security->getUser()
        ]);
        $html = $this->renderView(
            'task/components/pdf_export.html.twig',
            array(
                'tasks' => $tasks
            )
        );
        $pdfService->generate($html,'tasks');
    }

    #[Route('/new', name: 'task_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}
