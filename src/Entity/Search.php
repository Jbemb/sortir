<?php

namespace App\Entity;

use App\Repository\SearchRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Search
{
    private $campus;

    private $keywords;

    private $startDate;

    private $endDate;

    private $organiser;

    private $signedUp;

    private $notSignedUp;

    private $passedEvent;


    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOrganiser(): ?bool
    {
        return $this->organiser;
    }

    public function setOrganiser(bool $organiser): self
    {
        $this->organiser = $organiser;

        return $this;
    }

    public function getSignedUp(): ?bool
    {
        return $this->signedUp;
    }

    public function setSignedUp(bool $signedUp): self
    {
        $this->signedUp = $signedUp;

        return $this;
    }

    public function getNotSignedUp(): ?bool
    {
        return $this->notSignedUp;
    }

    public function setNotSignedUp(bool $notSignedUp): self
    {
        $this->notSignedUp = $notSignedUp;

        return $this;
    }

    public function getPassedEvent(): ?bool
    {
        return $this->passedEvent;
    }

    public function setPassedEvent(bool $passedEvent): self
    {
        $this->passedEvent = $passedEvent;

        return $this;
    }
}
