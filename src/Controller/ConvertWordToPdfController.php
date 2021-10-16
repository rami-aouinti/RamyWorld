<?php

namespace App\Controller;

use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\HobbyRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProfileRepository;
use App\Repository\SkillRepository;
use App\Repository\WorkRepository;
use App\Service\PdfGenerator;
use setasign\Fpdi\Fpdi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\Security;
use Fpdf;

/**
 * Class ConvertWordToPdfController
 */
class ConvertWordToPdfController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    /** KernelInterface $appKernel */
    private $appKernel;

    public function __construct(Security $security, KernelInterface $appKernel)
    {
        $this->security = $security;
        $this->appKernel = $appKernel;
    }

    #[Route('/convert/word/to/pdf', name: 'convert_word_to_pdf')]
    public function pdfAction(
        PdfGenerator         $genrator,
        ProfileRepository    $profileRepository,
        EducationRepository  $educationRepository,
        WorkRepository       $workRepository,
        LanguageRepository   $languageRepository,
        HobbyRepository      $hobbyRepository,
        ExperienceRepository $experienceRepository,
        SkillRepository      $skillRepository)
    {
        $data = [
            'profile' => $profileRepository->findOneBy([
                'user' => $this->security->getUser()
            ]),
            'educations' => $educationRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'projects' => $workRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'hobbies' => $hobbyRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'languages' => $languageRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'experiences' => $experienceRepository->findBy([
                'user' => $this->security->getUser()
            ]),
            'skills' => $skillRepository->findBy([
                'user' => $this->security->getUser()
            ])
        ];
        $genrator->generate('example', $data);
    }
}
