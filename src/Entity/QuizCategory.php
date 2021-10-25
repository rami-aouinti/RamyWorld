<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuizCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=QuizCategoryRepository::class)
 */
class QuizCategory
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=QuizQuestion::class, mappedBy="categories")
     */
    public $quizquestion;

    public function __toString(): string
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->quizquestion = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuizquestion(): Collection
    {
        return $this->quizquestion;
    }

    /**
     * @param mixed $quizquestion
     */
    public function setQuizquestion($quizquestion): void
    {
        $this->quizquestion = $quizquestion;
    }
}
