<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstname = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastname = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $mobile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $state = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $post_code = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $street = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $street_number = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $facebook = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitter = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $instagram = null;


    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @param string|null $mobile
     */
    public function setMobile(?string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return int|null
     */
    public function getPostCode(): ?int
    {
        return $this->post_code;
    }

    /**
     * @param int|null $post_code
     */
    public function setPostCode(?int $post_code): void
    {
        $this->post_code = $post_code;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return int|null
     */
    public function getStreetNumber(): ?int
    {
        return $this->street_number;
    }

    /**
     * @param int|null $street_number
     */
    public function setStreetNumber(?int $street_number): void
    {
        $this->street_number = $street_number;
    }

    /**
     * @return string|null
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @param string|null $facebook
     */
    public function setFacebook(?string $facebook): void
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string|null
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * @param string|null $twitter
     */
    public function setTwitter(?string $twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string|null
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * @param string|null $instagram
     */
    public function setInstagram(?string $instagram): void
    {
        $this->instagram = $instagram;
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
