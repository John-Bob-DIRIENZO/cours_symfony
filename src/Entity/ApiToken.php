<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->expiresAt = new \DateTime('+1 day');
        $this->token = bin2hex(random_bytes(60));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new \DateTime();
    }

//    public function setToken(string $token): self
//    {
//        $this->token = $token;
//
//        return $this;
//    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

//    public function setExpiresAt(\DateTimeInterface $expiresAt): self
//    {
//        $this->expiresAt = $expiresAt;
//
//        return $this;
//    }

    public function getUser(): ?User
    {
        return $this->user;
    }

//    public function setUser(?User $user): self
//    {
//        $this->user = $user;
//
//        return $this;
//    }
}
