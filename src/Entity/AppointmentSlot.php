<?php

namespace App\Entity;

use App\Repository\AppointmentSlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentSlotRepository::class)]
class AppointmentSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $time = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBooked = false;

    #[ORM\OneToOne(inversedBy: 'appointmentSlot', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function setTime(?\DateTime $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getIsBooked(): ?bool
    {
        return $this->isBooked;
    }

    public function setIsBooked(?bool $isBooked): static
    {
        $this->isBooked = $isBooked;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        $this->appointment = $appointment;

        return $this;
    }
}
