<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $appointmentNote = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $appointmentLink = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $informationalMessage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, TaskDetails>
     */
    #[ORM\OneToMany(targetEntity: TaskDetails::class, mappedBy: 'taskId')]
    private Collection $taskDetails;

    /**
     * @var Collection<int, VisaTypeProfile>
     */
    #[ORM\ManyToMany(targetEntity: VisaTypeProfile::class, inversedBy: 'tasks')]
    private Collection $visaType;

    public function __construct()
    {
        $this->taskDetails = new ArrayCollection();
        $this->visaType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, TaskDetails>
     */
    public function getTaskDetails(): Collection
    {
        return $this->taskDetails;
    }

    public function addTaskDetail(TaskDetails $taskDetail): static
    {
        if (!$this->taskDetails->contains($taskDetail)) {
            $this->taskDetails->add($taskDetail);
            $taskDetail->setTaskId($this);
        }

        return $this;
    }

    public function removeTaskDetail(TaskDetails $taskDetail): static
    {
        if ($this->taskDetails->removeElement($taskDetail)) {
            // set the owning side to null (unless already changed)
            if ($taskDetail->getTaskId() === $this) {
                $taskDetail->setTaskId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VisaTypeProfile>
     */
    public function getVisaType(): Collection
    {
        return $this->visaType;
    }

    public function addVisaType(VisaTypeProfile $visaType): static
    {
        if (!$this->visaType->contains($visaType)) {
            $this->visaType->add($visaType);
        }

        return $this;
    }

    public function removeVisaType(VisaTypeProfile $visaType): static
    {
        $this->visaType->removeElement($visaType);

        return $this;
    }
}
