<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public ?int $id;

    /**
     * @ORM\Column(type="text")
     */
    public ?string $message;

    /**
     * @ORM\Column(type="array")
     */
    public array $context = [];

    /**
     * @ORM\Column(type="smallint")
     */
    public ?int $level;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $levelName;

    /**
     * @ORM\Column(type="array")
     */
    public array $extra = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevelName(): ?string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): self
    {
        $this->levelName = $levelName;

        return $this;
    }

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }
}
