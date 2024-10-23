<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Length(
        min: 10,
        minMessage: "Trop court"
    )]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Constraints\NotBlank]
    private ?string $decription = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Constraints\Date]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 100)]
    private ?string $lieu = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxParticipants = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): static
    {
        $this->decription = $decription;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }
}
