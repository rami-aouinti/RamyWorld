<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use App\Service\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/project')]
class ProjectController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    private UploadFile $uploadFile;

    public function __construct(Security $security, UploadFile $uploadFile)
    {
        $this->security = $security;
        $this->uploadFile = $uploadFile;
    }

    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'project_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $project = new Project();
        $project->setAuthor($this->security->getUser());
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setLogo($this->uploadFile->upload($form->get('logo')->getData(), 'logo_projects'));
            $project->setFile($this->uploadFile->upload($form->get('file')->getData(), 'brochures_directory'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setLogo($this->uploadFile->upload($form->get('logo')->getData(), 'logo_projects'));
            $project->setFile($this->uploadFile->upload($form->get('file')->getData(), 'brochures_directory'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index', [], Response::HTTP_SEE_OTHER);
    }
}
