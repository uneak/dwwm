<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/admin/company', name: 'app_admin_company')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {

        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $company = $form->getData();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/company.html.twig', [
            'form' => $form,
        ]);
    }
}
