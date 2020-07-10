<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Entrer un titre s'il vous plaît!")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\GreaterThan("now")
     * @ORM\Column(type="datetime")
     */
    private $startDateTime;

    /**
     * @Assert\NotBlank(message="Entrer une durée s'il vous plaît!")
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @Assert\GreaterThan("yesterday")
     * @ORM\Column(type="date")
     */
    private $inscriptionLimit;

    /**
     * @Assert\NotBlank(message="Entrer le nombre de participants maximum s'il vous plaît!")
     * @ORM\Column(type="integer")
     */
    private $maxParticipant;

    /**
     * @Assert\NotBlank(message="Entrer un description s'il vous plaît!")
     * @ORM\Column(type="text")
     */
    private $eventInfo;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventsOrganized")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organiser;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="events")
     */
    private $participants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reasonDelete;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isArchived = false;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInscriptionLimit(): ?\DateTimeInterface
    {
        return $this->inscriptionLimit;
    }

    public function setInscriptionLimit(\DateTimeInterface $inscriptionLimit): self
    {
        $this->inscriptionLimit = $inscriptionLimit;

        return $this;
    }

    public function getMaxParticipant(): ?int
    {
        return $this->maxParticipant;
    }

    public function setMaxParticipant(int $maxParticipant): self
    {
        $this->maxParticipant = $maxParticipant;

        return $this;
    }

    public function getEventInfo(): ?string
    {
        return $this->eventInfo;
    }

    public function setEventInfo(string $eventInfo): self
    {
        $this->eventInfo = $eventInfo;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganiser(): ?User
    {
        return $this->organiser;
    }

    public function setOrganiser(?User $organiser): self
    {
        $this->organiser = $organiser;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsArchived()
    {
        return $this->isArchived;
    }

    /**
     * @param mixed $isArchived
     */
    public function setIsArchived($isArchived): void
    {
        $this->isArchived = $isArchived;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }

    public function getReasonDelete(): ?string
    {
        return $this->reasonDelete;
    }

    public function setReasonDelete(?string $reasonDelete): self
    {
        $this->reasonDelete = $reasonDelete;

        return $this;
    }
}
