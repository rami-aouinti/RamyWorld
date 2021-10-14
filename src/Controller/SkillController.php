<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use App\Service\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/skill')]
class SkillController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response
    {
        return $this->render('skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'skill_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        // Collect data
        $donnees = json_decode($request->getContent());

        if(isset($donnees->name) && !empty($donnees->name))
        {
            $skill = new Skill();
            $skill->setUser($this->security->getUser());
            $code = 201;
            $level = substr($donnees->level, 0, 2);

            // Save in Database
            $skill->setName($donnees->name);
            $skill->setLevel((int)$level);
            $em = $this->getDoctrine()->getManager();
            $em->persist($skill);
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

    #[Route('/{id}', name: 'skill_show', methods: ['GET'])]
    public function show(Skill $skill): Response
    {
        return $this->render('skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    #[Route('/{id}/edit', name: 'skill_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('skill_index', [], Response::HTTP_SEE_OTHER);
    }
}
