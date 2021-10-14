<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/experience')]
class ExperienceController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'experience_index', methods: ['GET'])]
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('experience/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'experience_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        // Collect data
        $donnees = json_decode($request->getContent());

        if(isset($donnees->name) && !empty($donnees->name))
        {
            $experience = new Experience();
            $experience->setUser($this->security->getUser());
            $code = 201;
            // Save in Database
            $experience->setCompany($donnees->company);
            $experience->setTitle($donnees->title);
            $experience->setDescription($donnees->description);
            $experience->setStartDate(new DateTime($donnees->startDate));
            $experience->setEndDate(new DateTime($donnees->endDate));
            $em = $this->getDoctrine()->getManager();
            $em->persist($experience);
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

    #[Route('/{id}', name: 'experience_show', methods: ['GET'])]
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}/edit', name: 'experience_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Experience $experience): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'experience_delete', methods: ['POST'])]
    public function delete(Request $request, Experience $experience): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
