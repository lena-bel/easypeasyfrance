<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AppointmentRepository;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $preferredDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $preferredTime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }
    

    public function getPreferredDate(): ?\DateTime
    {
        return $this->preferredDate;
    }

    public function setPreferredDate(?\DateTime $preferredDate): static
    {
        $this->preferredDate = $preferredDate;

        return $this;
    }

    public function getPreferredTime(): ?\DateTime
    {
        return $this->preferredTime;
    }

    public function setPreferredTime(?\DateTime $preferredTime): static
    {
        $this->preferredTime = $preferredTime;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        // $this->createdAt = $createdAt;
         if ($this->createdAt === null) {
            $this->createdAt = new DateTime();
        }

        // return $this;
    }

    
}
