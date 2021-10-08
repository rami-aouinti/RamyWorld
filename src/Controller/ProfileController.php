<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use App\Service\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profile')]
class ProfileController extends AbstractController
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

    #[Route('/', name: 'profile_index', methods: ['GET'])]
    public function index(ProfileRepository $profileRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'profiles' => $profileRepository->findAll(),
        ]);
    }

    #[Route('/setting', name: 'profile_setting', methods: ['GET'])]
    public function setting(ProfileRepository $profileRepository): Response
    {
        return $this->render('profile/setting.html.twig', [
            'profiles' => $profileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'profile_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setImage($this->uploadFile->upload($form->get('image')->getData(), 'profile_image'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/new.html.twig', [
            'profile' => $profile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'profile_show', methods: ['GET'])]
    public function show(Profile $profile): Response
    {
        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/{id}/edit', name: 'profile_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setImage($this->uploadFile->upload($form->get('image')->getData(), 'profile_image'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form,
        ]);
    }

    #[Route('/save', name: 'profile_save', methods: ['GET','POST'])]
    public function save(Request $request, ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy([
            'user' => $this->security->getUser()
        ]);

        $profile->setFirstname($request->request->get('firstname'));
        $profile->setLastname($request->request->get('lastname'));
        $profile->setStreet($request->request->get('street'));
        $profile->setStreetNumber($request->request->get('street_number'));
        $profile->setState($request->request->get('state'));
        $profile->setCountry($request->request->get('choices-country'));
        //$profile->setImage($this->uploadFile->upload($form->get('image')->getData(), 'profile_image'));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'profile_delete', methods: ['POST'])]
    public function delete(Request $request, Profile $profile): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
