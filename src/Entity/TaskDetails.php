<?php

namespace App\Entity;

use App\Repository\TaskDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskDetailsRepository::class)]
class TaskDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskDetails')]
    private ?Task $taskId = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $appointmentNote = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $appointmentLink = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $informationalMessage = null;

    #[ORM\Column(nullable: true)]
    private ?array $externalLinks = null;

    #[ORM\Column(nullable: true)]
    private ?array $documentCheckList = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?array $steps = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTaskId(): ?Task
    {
        return $this->taskId;
    }

    public function setTaskId(?Task $taskId): static
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getAppointmentNote(): ?string
    {
        return $this->appointmentNote;
    }

    public function setAppointmentNote(?string $appointmentNote): static
    {
        $this->appointmentNote = $appointmentNote;

        return $this;
    }

    public function getAppointmentLink(): ?string
    {
        return $this->appointmentLink;
    }

    public function setAppointmentLink(?string $appointmentLink): static
    {
        $this->appointmentLink = $appointmentLink;

        return $this;
    }

    public function getInformationalMessage(): ?string
    {
        return $this->informationalMessage;
    }

    public function setInformationalMessage(?string $informationalMessage): static
    {
        $this->informationalMessage = $informationalMessage;

        return $this;
    }

    public function getExternalLinks(): ?array
    {
        return $this->externalLinks;
    }

    public function setExternalLinks(?array $externalLinks): static
    {
        $this->externalLinks = $externalLinks;

        return $this;
    }

    public function getDocumentCheckList(): ?array
    {
        return $this->documentCheckList;
    }

    public function setDocumentCheckList(?array $documentCheckList): static
    {
        $this->documentCheckList = $documentCheckList;

        return $this;
    }

    public function getSteps(): ?array
    {
        return $this->steps;
    }

    public function setSteps(?array $steps): static
    {
        $this->steps = $steps;

        return $this;
    }
}
