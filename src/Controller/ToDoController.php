<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ToDoController extends AbstractController
{
    #[Route('/', name: 'app_todo_list')]
    public function index(): Response
    {
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/edit/{id}', name: 'app_todo_edit')]
    public function edit(): Response
    {
        return $this->render('todo/edit.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/create', name: 'app_todo_create')]
    public function create(): Response
    {
        return $this->render('todo/create.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
