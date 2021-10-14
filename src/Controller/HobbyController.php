<?php

namespace App\Controller;

use App\Entity\Hobby;
use App\Form\HobbyType;
use App\Repository\HobbyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/hobby')]
class HobbyController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'hobby_index', methods: ['GET'])]
    public function index(HobbyRepository $hobbyRepository): Response
    {
        return $this->render('hobby/index.html.twig', [
            'hobbies' => $hobbyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'hobby_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        // Collect data
        $donnees = json_decode($request->getContent());

        if(isset($donnees->name) && !empty($donnees->name))
        {
            $hobby = new Hobby();
            $hobby->setUser($this->security->getUser());
            $code = 201;

            // Save in Database
            $hobby->setName($donnees->name);
            $em = $this->getDoctrine()->getManager();
            $em->persist($hobby);
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

    #[Route('/{id}', name: 'hobby_show', methods: ['GET'])]
    public function show(Hobby $hobby): Response
    {
        return $this->render('hobby/show.html.twig', [
            'hobby' => $hobby,
        ]);
    }

    #[Route('/{id}/edit', name: 'hobby_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Hobby $hobby): Response
    {
        $form = $this->createForm(HobbyType::class, $hobby);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hobby_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hobby/edit.html.twig', [
            'hobby' => $hobby,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'hobby_delete', methods: ['POST'])]
    public function delete(Request $request, Hobby $hobby): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hobby->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hobby);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hobby_index', [], Response::HTTP_SEE_OTHER);
    }
}
