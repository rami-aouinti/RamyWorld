<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=EducationRepository::class)
 */
class Education
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $school;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $grad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $end_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="education")
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getGrad(): ?string
    {
        return $this->grad;
    }

    public function setGrad(string $grad): self
    {
        $this->grad = $grad;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
