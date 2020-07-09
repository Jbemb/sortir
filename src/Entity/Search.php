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

    private $isOrganiser;

    private $isSignedUp;

    private $isNotSignedUp;

    private $isPassedEvent;


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

    public function isOrganiser(): ?bool
    {
        return $this->isOrganiser;
    }

    public function setIsOrganiser(bool $isOrganiser): self
    {
        $this->isOrganiser = $isOrganiser;

        return $this;
    }

    public function isSignedUp(): ?bool
    {
        return $this->isSignedUp;
    }

    public function setIsSignedUp(bool $isSignedUp): self
    {
        $this->isSignedUp = $isSignedUp;

        return $this;
    }

    public function isNotSignedUp(): ?bool
    {
        return $this->isNotSignedUp;
    }

    public function setIsNotSignedUp(bool $isNotSignedUp): self
    {
        $this->isNotSignedUp = $isNotSignedUp;

        return $this;
    }

    public function isPassedEvent(): ?bool
    {
        return $this->isPassedEvent;
    }

    public function setIsPassedEvent(bool $isPassedEvent): self
    {
        $this->isPassedEvent = $isPassedEvent;

        return $this;
    }
}
