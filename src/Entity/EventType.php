<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=EventTypeRepository::class)
 */
class EventType
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
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private mixed $backgroundColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private mixed $borderColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private mixed $textColor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param mixed $backgroundColor
     */
    public function setBackgroundColor($backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return mixed
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param mixed $borderColor
     */
    public function setBorderColor($borderColor): void
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return mixed
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param mixed $textColor
     */
    public function setTextColor($textColor): void
    {
        $this->textColor = $textColor;
    }
}
