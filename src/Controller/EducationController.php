<?php

namespace App\Controller;

use App\Entity\Education;
use App\Form\EducationType;
use App\Repository\EducationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/education')]
class EducationController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'education_index', methods: ['GET'])]
    public function index(EducationRepository $educationRepository): Response
    {
        return $this->render('education/index.html.twig', [
            'education' => $educationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'education_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        // Collect data
        $donnees = json_decode($request->getContent());

        if(isset($donnees->name) && !empty($donnees->name))
        {
            $education = new Education();
            $education->setUser($this->security->getUser());
            $code = 201;

            // Save in Database
            $education->setSchool($donnees->school);
            $education->setGrad($donnees->grad);
            $education->setDescription($donnees->description);
            $education->setStartDate(new DateTime($donnees->startDate));
            $education->setEndDate(new DateTime($donnees->endDate));
            $em = $this->getDoctrine()->getManager();
            $em->persist($education);
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

    #[Route('/{id}', name: 'education_show', methods: ['GET'])]
    public function show(Education $education): Response
    {
        return $this->render('education/show.html.twig', [
            'education' => $education,
        ]);
    }

    #[Route('/{id}/edit', name: 'education_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Education $education): Response
    {
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('education_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('education/edit.html.twig', [
            'education' => $education,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'education_delete', methods: ['POST'])]
    public function delete(Request $request, Education $education): Response
    {
        if ($this->isCsrfTokenValid('delete'.$education->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($education);
            $entityManager->flush();
        }

        return $this->redirectToRoute('education_index', [], Response::HTTP_SEE_OTHER);
    }
}
