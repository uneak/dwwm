<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\DateSchedules;
use App\Form\CompanyType;
use App\Form\DateScheduleType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DateScheduleController extends AbstractController
{
    #[Route('/admin/company/{id}/schedule/date/new', name: 'app_admin_date_schedule')]
    public function index(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $company = $em->getRepository(Company::class)->find($id);

        $dateSchedules = new DateSchedules();
        $dateSchedules->setCompany($company);

        $form = $this->createForm(DateScheduleType::class, $dateSchedules);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateSchedules = $form->getData();
            $em->persist($dateSchedules);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('admin/date_schedule.html.twig', [
            'form' => $form,
        ]);
    }
}
