<?php

    namespace App\Controller;

    use App\Service\TodoList;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;


    class ToDoController extends AbstractController
    {
        #[Route(path: '/', name: 'app_todo_list')]
        public function index(TodoList $todoList): Response
        {
            return $this->render('todo/index.html.twig', [
                'tasks' => $todoList->getTasks(),
            ]);
        }



        #[Route('/edit/{id}', name: 'app_todo_edit', requirements: ['id' => '\d+'])]
        public function edit(TodoList $todoList, Request $request, int $id): Response
        {
            if ($request->isMethod('POST')) {
                $title = $request->request->get('title');
                $description = $request->request->get('description');

                $todoList->updateTask($id, $title, $description);

                return $this->redirectToRoute('app_todo_list');
            }


            $task = $todoList->getTask($id);
            if (!$task) {
                throw $this->createNotFoundException('Task not found');
            }

            return $this->render('todo/edit.html.twig', [
                'id'          => $task->getId(),
                'title'       => $task->getTitle(),
                'description' => $task->getDescription(),
            ]);
        }


        #[Route('/create', name: 'app_todo_create')]
        public function create(TodoList $todoList, Request $request): Response
        {
            if ($request->isMethod('POST')) {
                $title = $request->request->get('title');
                $description = $request->request->get('description');

                $todoList->addTask($title, $description);

                return $this->redirectToRoute('app_todo_list');
            }

            return $this->render('todo/create.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }

        #[Route('/delete/{id}', name: 'app_todo_delete')]
        public function delete(TodoList $todoList, int $id): Response
        {
            $todoList->deleteTask($id);

            return $this->redirectToRoute('app_todo_list');
        }

        #[Route('/toggle/{id}', name: 'app_todo_toggle')]
        public function toggle(TodoList $todoList, int $id): Response
        {
            $todoList->toggleTask($id);

            return $this->redirectToRoute('app_todo_list');
        }
    }
