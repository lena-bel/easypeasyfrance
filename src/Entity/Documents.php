<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Task;
use App\Repository\DocumentsRepository;
// use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
class Documents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'documents')]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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
 * @return Collection<int, Task>
 */
public function getTasks(): Collection
{
    return $this->tasks;
}

public function addTask(Task $task): static
{
    if (!$this->tasks->contains($task)) {
        $this->tasks->add($task);
        $task->addDocument($this); // call the correct method
    }
    return $this;
}

public function removeTask(Task $task): static
{
    if ($this->tasks->removeElement($task)) {
        $task->removeDocument($this); // call the correct method
    }
    return $this;
}
}
