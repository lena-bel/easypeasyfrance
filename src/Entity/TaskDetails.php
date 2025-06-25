<?php

namespace App\Entity;

use App\Repository\TaskDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskDetailsRepository::class)]
class TaskDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(nullable: true)]
    private ?int $stepOrder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRequired = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'taskDetails')]
    private ?Task $taskId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStepOrder(): ?int
    {
        return $this->stepOrder;
    }

    public function setStepOrder(?int $stepOrder): static
    {
        $this->stepOrder = $stepOrder;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function isRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(?bool $isRequired): static
    {
        $this->isRequired = $isRequired;

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
}
