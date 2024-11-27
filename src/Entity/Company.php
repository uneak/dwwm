<?php

    namespace App\Entity;

    use App\Repository\CompanyRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: CompanyRepository::class)]
    class Company
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $name = null;

        /**
         * @var Collection<int, WeekSchedules>
         */
        #[ORM\OneToMany(targetEntity: WeekSchedules::class, mappedBy: 'company', orphanRemoval: true)]
        private Collection $weekSchedules;

        /**
         * @var Collection<int, DateSchedules>
         */
        #[ORM\OneToMany(targetEntity: DateSchedules::class, mappedBy: 'company', orphanRemoval: true)]
        private Collection $dateSchedules;

        public function __construct()
        {
            $this->weekSchedules = new ArrayCollection();
            $this->dateSchedules = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function setName(string $name): static
        {
            $this->name = $name;

            return $this;
        }

        /**
         * @return Collection<int, WeekSchedules>
         */
        public function getWeekSchedules(): Collection
        {
            return $this->weekSchedules;
        }

        public function addWeekSchedule(WeekSchedules $weekSchedule): static
        {
            if (!$this->weekSchedules->contains($weekSchedule)) {
                $this->weekSchedules->add($weekSchedule);
                $weekSchedule->setCompany($this);
            }

            return $this;
        }

        public function removeWeekSchedule(WeekSchedules $weekSchedule): static
        {
            if ($this->weekSchedules->removeElement($weekSchedule)) {
                // set the owning side to null (unless already changed)
                if ($weekSchedule->getCompany() === $this) {
                    $weekSchedule->setCompany(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, DateSchedules>
         */
        public function getDateSchedules(): Collection
        {
            return $this->dateSchedules;
        }

        public function addDateSchedule(DateSchedules $dateSchedule): static
        {
            if (!$this->dateSchedules->contains($dateSchedule)) {
                $this->dateSchedules->add($dateSchedule);
                $dateSchedule->setCompany($this);
            }

            return $this;
        }

        public function removeDateSchedule(DateSchedules $dateSchedule): static
        {
            if ($this->dateSchedules->removeElement($dateSchedule)) {
                // set the owning side to null (unless already changed)
                if ($dateSchedule->getCompany() === $this) {
                    $dateSchedule->setCompany(null);
                }
            }

            return $this;
        }
    }
