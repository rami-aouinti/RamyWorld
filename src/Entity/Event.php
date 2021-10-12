<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private ?string $title;

    /**
     * @ORM\Column(type="text", nullable="true")
     */
    private mixed $description;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $end_date;

    /**
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private ?string $url;

    /**
     * @ORM\Column(type="boolean", nullable="true", nullable="true")
     */
    private ?bool $all_day;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     */
    private ?User $user;

    /**
     * @ORM\OneToOne(targetEntity=EventType::class, cascade={"persist", "remove"})
     */
    private ?EventType $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllDay()
    {
        return $this->all_day;
    }

    /**
     * @param mixed $all_day
     */
    public function setAllDay($all_day): void
    {
        $this->all_day = $all_day;
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

    public function getType(): ?EventType
    {
        return $this->type;
    }

    public function setType(?EventType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
