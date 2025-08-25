<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LinksRepository;
// use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: LinksRepository::class)]
class Links
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'links')]
    private Collection $tasks;

    public function __construct()
{
    $this->tasks = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

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
        $task->addLink($this);
    }
    return $this;
}

public function removeTask(Task $task): static
{
    if ($this->tasks->removeElement($task)) {
        $task->removeLink($this);
    }
    return $this;
}
}
