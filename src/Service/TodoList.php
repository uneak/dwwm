<?php

    namespace App\Service;

    use App\Entity\Task;
    use App\Repository\TaskRepository;
    use Doctrine\ORM\EntityManagerInterface;

    class TodoList
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly TaskRepository $taskRepository
        ) { }

        public function getTasks(): array
        {
            return $this->taskRepository->findAll();
        }

        public function getTask(int $id): ?Task
        {
            return $this->taskRepository->find($id);
        }

        public function updateTask(int $id, string $title = null, string $description = null, ?bool $isComplete = null): void
        {
            $task = $this->getTask($id);

            if ($title !== null) {
                $task->setTitle($title);
            }
            if ($description !== null) {
                $task->setDescription($description);
            }
            if ($isComplete !== null) {
                $task->setComplete($isComplete);
            }

            $entityManager = $this->entityManager;
            $entityManager->persist($task);
            $entityManager->flush();
        }


        public function addTask(string $title, string $description = null, bool $isComplete = false): void
        {
            $task = new Task();
            $task->setTitle($title);
            $task->setDescription($description);
            $task->setComplete($isComplete);

            $entityManager = $this->entityManager;
            $entityManager->persist($task);
            $entityManager->flush();
        }

        public function toggleTask(int $id): void
        {
            $task = $this->getTask($id);
            $task->setComplete(!$task->isComplete());

            $entityManager = $this->entityManager;
            $entityManager->persist($task);
            $entityManager->flush();
        }

        public function deleteTask(int $id): void
        {
            $task = $this->getTask($id);

            $entityManager = $this->entityManager;
            $entityManager->remove($task);
            $entityManager->flush();
        }
    }
