<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuizScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=QuizScoreRepository::class)
 */
class QuizScore
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="scores")
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=QuizCategory::class, inversedBy="categories")
     */
    private ?QuizCategory $category;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $score;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $level;

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return QuizCategory|null
     */
    public function getCategory(): ?QuizCategory
    {
        return $this->category;
    }

    /**
     * @param QuizCategory|null $category
     */
    public function setCategory(?QuizCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int|null $level
     */
    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }
}
