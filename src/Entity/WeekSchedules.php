<?php

    namespace App\Entity;

    use App\Repository\WeekSchedulesRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: WeekSchedulesRepository::class)]
    class WeekSchedules
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $startedAt = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $EndedAt = null;

        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private ?WeekDays $weekDay = null;

        #[ORM\ManyToOne(inversedBy: 'weekSchedules')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Company $company = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getStartedAt(): ?\DateTimeInterface
        {
            return $this->startedAt;
        }

        public function setStartedAt(\DateTimeInterface $startedAt): static
        {
            $this->startedAt = $startedAt;

            return $this;
        }

        public function getEndedAt(): ?\DateTimeInterface
        {
            return $this->EndedAt;
        }

        public function setEndedAt(\DateTimeInterface $EndedAt): static
        {
            $this->EndedAt = $EndedAt;

            return $this;
        }

        public function getWeekDay(): ?WeekDays
        {
            return $this->weekDay;
        }

        public function setWeekDay(?WeekDays $weekDay): static
        {
            $this->weekDay = $weekDay;

            return $this;
        }

        public function getCompany(): ?Company
        {
            return $this->company;
        }

        public function setCompany(?Company $company): static
        {
            $this->company = $company;

            return $this;
        }
    }
