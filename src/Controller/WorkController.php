<?php

namespace App\Controller;

use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/work')]
class WorkController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'work_index', methods: ['GET'])]
    public function index(WorkRepository $workRepository): Response
    {
        return $this->render('work/index.html.twig', [
            'works' => $workRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'work_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        // Collect data
        $donnees = json_decode($request->getContent());

        if(isset($donnees->name) && !empty($donnees->name))
        {
            $work = new Work();
            $work->setUser($this->security->getUser());
            $code = 201;
            $level = substr($donnees->level, 0, 2);

            // Save in Database
            $work->setName($donnees->name);
            $work->setDescription($donnees->description);
            $work->setRepository($donnees->repository);
            $work->setStartDate(new DateTime($donnees->startDate));
            $work->setEndDate(new DateTime($donnees->endDate));
            $em = $this->getDoctrine()->getManager();
            $em->persist($work);
            $em->flush();

            // Return Code
            return new JsonResponse([
                'status' => $code
            ]);
        }else{
            // Incomplete Data
            return new Response('Données incomplètes', 404);
        }
    }

    #[Route('/{id}', name: 'work_show', methods: ['GET'])]
    public function show(Work $work): Response
    {
        return $this->render('work/show.html.twig', [
            'work' => $work,
        ]);
    }

    #[Route('/{id}/edit', name: 'work_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Work $work): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('work_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('work/edit.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'work_delete', methods: ['POST'])]
    public function delete(Request $request, Work $work): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('work_index', [], Response::HTTP_SEE_OTHER);
    }
}
