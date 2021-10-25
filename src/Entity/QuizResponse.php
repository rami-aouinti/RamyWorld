<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuizResponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=QuizResponseRepository::class)
 */
class QuizResponse
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $response;

    /**
     * @ORM\Column(type="boolean")
     */
    public ?bool $exacte;

    /**
     * @ORM\ManyToOne(targetEntity=QuizQuestion::class, inversedBy="answers")
     */
    public ?QuizQuestion $quizquestion;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param string|null $response
     */
    public function setResponse(?string $response): void
    {
        $this->response = $response;
    }

    /**
     * @return QuizQuestion|null
     */
    public function getQuizquestion(): ?QuizQuestion
    {
        return $this->quizquestion;
    }

    /**
     * @param QuizQuestion|null $quizquestion
     */
    public function setQuizquestion(?QuizQuestion $quizquestion): void
    {
        $this->quizquestion = $quizquestion;
    }

    /**
     * @return bool|null
     */
    public function getExacte(): ?bool
    {
        return $this->exacte;
    }

    /**
     * @param bool|null $exacte
     */
    public function setExacte(?bool $exacte): void
    {
        $this->exacte = $exacte;
    }
}
