<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $specialty = null;

    #[ORM\Column(length: 255)]
    private ?string $officeRegion = null;

    #[ORM\Column(length: 255)]
    private ?string $officeAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $officePhone = null;

    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'doctor')]
    private Collection $Consultations;

    #[ORM\OneToOne(mappedBy: 'Doctor', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Blog::class, mappedBy: 'doctor', orphanRemoval: true)]
    private Collection $blogs;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'Doctor')]
    private Collection $appointments;

    #[ORM\ManyToMany(targetEntity: Secretary::class, inversedBy: 'doctors')]
    private Collection $Secretarys;

    public function __construct()
    {
        $this->Consultations = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->Secretarys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getOfficeRegion(): ?string
    {
        return $this->officeRegion;
    }

    public function setOfficeRegion(string $officeRegion): static
    {
        $this->officeRegion = $officeRegion;

        return $this;
    }

    public function getOfficeAddress(): ?string
    {
        return $this->officeAddress;
    }

    public function setOfficeAddress(string $officeAddress): static
    {
        $this->officeAddress = $officeAddress;

        return $this;
    }

    public function getOfficePhone(): ?string
    {
        return $this->officePhone;
    }

    public function setOfficePhone(string $officePhone): static
    {
        $this->officePhone = $officePhone;

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->Consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->Consultations->contains($consultation)) {
            $this->Consultations->add($consultation);
            $consultation->setDoctor($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->Consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getDoctor() === $this) {
                $consultation->setDoctor(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setDoctor(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getDoctor() !== $this) {
            $user->setDoctor($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setDoctor($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getDoctor() === $this) {
                $blog->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setDoctor($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getDoctor() === $this) {
                $appointment->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Secretary>
     */
    public function getSecretarys(): Collection
    {
        return $this->Secretarys;
    }

    public function addSecretary(Secretary $secretary): static
    {
        if (!$this->Secretarys->contains($secretary)) {
            $this->Secretarys->add($secretary);
        }

        return $this;
    }

    public function removeSecretary(Secretary $secretary): static
    {
        $this->Secretarys->removeElement($secretary);

        return $this;
    }
}
