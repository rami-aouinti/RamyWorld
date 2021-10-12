<?php

namespace App\Controller;

use App\Entity\Education;
use App\Entity\Resume;
use App\Form\ResumeType;
use App\Message\SmsNotification;
use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\HobbyRepository;
use App\Repository\LanguageRepository;
use App\Repository\ResumeRepository;
use App\Repository\SkillRepository;
use App\Repository\WorkRepository;
use App\Service\PDFService;
use App\Service\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/resume')]
class ResumeController extends AbstractController
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

    #[Route('/', name: 'resume_index', methods: ['GET'])]
    public function index(ResumeRepository $resumeRepository): Response
    {
        return $this->render('resume/index.html.twig', [
            'resumes' => $resumeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'resume_new', methods: ['GET','POST'])]
    public function new(
        Request $request,
        EducationRepository $educationRepository,
        ExperienceRepository $experienceRepository,
        SkillRepository $skillRepository,
        WorkRepository $workRepository,
        LanguageRepository $languageRepository,
        HobbyRepository $hobbyRepository,
        MessageBusInterface $bus
    ): Response
    {
        $bus->dispatch(new SmsNotification('Look! I created a message!'));
        $this->dispatchMessage(new SmsNotification('Look! I created a message!'));

        $resume = new Resume();
        $resume->setUser($this->security->getUser());
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resume);
            $entityManager->flush();

            return $this->redirectToRoute('resume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resume/new.html.twig', [
            'resume' => $resume,
            'education' => $educationRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'experience' => $experienceRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'skill' => $skillRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'project' => $workRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'hobby' => $hobbyRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'language' => $languageRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'form' => $form,
        ]);
    }

    #[Route('/save', name: 'resume_save', methods: ['GET','POST'])]
    public function save(PDFService $PDFService)
    {
        $html = $this->renderView(
            'resume/template_resume.html.twig',
            array(
                'someDataToView' => 'Something'
            )
        );
        $PDFService->generate($html);
    }

    #[Route('/{id}', name: 'resume_show', methods: ['GET'])]
    public function show(Resume $resume): Response
    {
        return $this->render('resume/show.html.twig', [
            'resume' => $resume,
        ]);
    }

    #[Route('/{id}/edit', name: 'resume_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Resume $resume): Response
    {
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resume/edit.html.twig', [
            'resume' => $resume,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'resume_delete', methods: ['POST'])]
    public function delete(Request $request, Resume $resume): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resume->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resume_index', [], Response::HTTP_SEE_OTHER);
    }
}
