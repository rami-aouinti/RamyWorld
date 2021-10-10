<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Symfony\Component\Security\Core\Security;

/**
 * Class Show Profile
 */
class ShowProfile
{

    /**
     * @var Security
     */
    private Security $security;

    private  ProfileRepository $profileRepository;

    public function __construct(Security $security, ProfileRepository $profileRepository)
    {
        $this->security = $security;
        $this->profileRepository = $profileRepository;
    }


    public function collection(): ?Profile
    {
        if (!$this->security->getUser()) {
            return null;
        } else {
            $profile = $this->profileRepository->findOneBy([
                'user' => $this->security->getUser()
            ]);
            return $profile;
        }
    }

}
