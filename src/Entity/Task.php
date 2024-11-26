<?php

    namespace App\Entity;

    use App\Repository\TaskRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: TaskRepository::class)]
    class Task
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $title = null;

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null;

        #[ORM\Column]
        private ?bool $isComplete = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(string $title): static
        {
            $this->title = $title;

            return $this;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(?string $description): static
        {
            $this->description = $description;

            return $this;
        }

        public function isComplete(): ?bool
        {
            return $this->isComplete;
        }

        public function setComplete(bool $isComplete): static
        {
            $this->isComplete = $isComplete;

            return $this;
        }
    }
