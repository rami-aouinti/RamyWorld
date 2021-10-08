<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="message")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $receiver;

    public function __construct()
    {
        $this->file = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(File $file): self
    {
        if (!$this->file->contains($file)) {
            $this->file[] = $file;
            $file->setMessage($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getMessage() === $this) {
                $file->setMessage(null);
            }
        }

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
