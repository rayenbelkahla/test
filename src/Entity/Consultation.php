<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $startAt = null;

    #[ORM\Column]
    private ?int $endAt = null;

    #[ORM\OneToOne(inversedBy: 'consultation', cascade: ['persist', 'remove'])]
    private ?Prescription $Prescription = null;

    #[ORM\OneToMany(targetEntity: Analyse::class, mappedBy: 'consultation')]
    private Collection $Analyses;

    #[ORM\OneToOne(mappedBy: 'Consultation', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    #[ORM\ManyToOne(inversedBy: 'Consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'Consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    public function __construct()
    {
        $this->Analyses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?int
    {
        return $this->startAt;
    }

    public function setStartAt(int $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?int
    {
        return $this->endAt;
    }

    public function setEndAt(int $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPrescription(): ?Prescription
    {
        return $this->Prescription;
    }

    public function setPrescription(?Prescription $Prescription): static
    {
        $this->Prescription = $Prescription;

        return $this;
    }

    /**
     * @return Collection<int, Analyse>
     */
    public function getAnalyses(): Collection
    {
        return $this->Analyses;
    }

    public function addAnalysis(Analyse $analysis): static
    {
        if (!$this->Analyses->contains($analysis)) {
            $this->Analyses->add($analysis);
            $analysis->setConsultation($this);
        }

        return $this;
    }

    public function removeAnalysis(Analyse $analysis): static
    {
        if ($this->Analyses->removeElement($analysis)) {
            // set the owning side to null (unless already changed)
            if ($analysis->getConsultation() === $this) {
                $analysis->setConsultation(null);
            }
        }

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        // unset the owning side of the relation if necessary
        if ($appointment === null && $this->appointment !== null) {
            $this->appointment->setConsultation(null);
        }

        // set the owning side of the relation if necessary
        if ($appointment !== null && $appointment->getConsultation() !== $this) {
            $appointment->setConsultation($this);
        }

        $this->appointment = $appointment;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }
}
