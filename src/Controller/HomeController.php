<?php

    namespace App\Controller;

    use App\Entity\Company;
    use App\Entity\DateSchedules;
    use App\Entity\WeekDays;
    use App\Entity\WeekSchedules;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_home')]
        public function index(EntityManagerInterface $em): Response
        {
            $companies = $em->getRepository(Company::class)->findAll();

            return $this->render('index.html.twig', [
                'companies' => $companies,
            ]);
        }

        #[Route('/schedule/{id}', name: 'app_schedule')]
        public function schedule(EntityManagerInterface $em, int $id): Response
        {
            $company = $em->getRepository(Company::class)->find($id);
            $weekDaysRepository = $em->getRepository(WeekDays::class);
            $weekSchedulesRepository = $em->getRepository(WeekSchedules::class);
            $dateSchedulesRepository = $em->getRepository(DateSchedules::class);

            $weekSchedules = [];
            $days = $weekDaysRepository->findAll();
            foreach ($days as $day) {
                $weekSchedules[$day->getName()] = $weekSchedulesRepository->findBy([
                    'company' => $company->getId(),
                    'weekDay' => $day->getId()
                ]);
            }

            $dates = $dateSchedulesRepository->findBy(['company' => $company->getId()]);
            $dateSchedules = [];

            foreach ($dates as $schedule) {
                $dateKey = $schedule->getDate()->format('Y-m-d');
                if (!isset($dateSchedules[$dateKey])) {
                    $dateSchedules[$dateKey] = [];
                }
                $dateSchedules[$dateKey][] = $schedule;
            }


            $testDate = new \DateTime();

            return $this->render('schedule.html.twig', [
                'company' => $company,
                'date' => $testDate,
                'weekSchedules' => $weekSchedules,
                'dateSchedules' => $dateSchedules,
                'isOpened' => $this->isCompanyOpen($em, $company, $testDate)
            ]);
        }

        private function isCompanyOpen(EntityManagerInterface $em, Company $company, \DateTime $dateTime): bool
        {
            $weekDaysRepository = $em->getRepository(WeekDays::class);
            $weekSchedulesRepository = $em->getRepository(WeekSchedules::class);
            $dateSchedulesRepository = $em->getRepository(DateSchedules::class);

            $dateKey = $dateTime->format('Y-m-d');
            $currentTime = $dateTime->format('H:i:s');

            // 1. Vérification des horaires exceptionnels pour cette date
            $dateSchedules = $dateSchedulesRepository->findBy([
                'company' => $company->getId(),
                'date'    => new \DateTime($dateKey)
            ]);

            foreach ($dateSchedules as $schedule) {
                $start = $schedule->getStartedAt()->format('H:i:s');
                $end = $schedule->getEndedAt()->format('H:i:s');

                if ($currentTime >= $start && $currentTime <= $end) {
                    if ($schedule->isOpen()) {
                        // Retourne immédiatement "ouvert" si une ouverture exceptionnelle est trouvée
                        return true;
                    } else {
                        // Retourne immédiatement "fermé" si une fermeture exceptionnelle est trouvée
                        return false;
                    }
                }
            }

            $dayName = $dateTime->format('l'); // Jour de la semaine (ex: "Monday", "Tuesday")

            $dayMap = [
                'Monday'    => 'Lundi',
                'Tuesday'   => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday'  => 'Jeudi',
                'Friday'    => 'Vendredi',
                'Saturday'  => 'Samedi',
                'Sunday'    => 'Dimanche',
            ];

            $weekDay = $weekDaysRepository->findOneBy(['name' => $dayMap[$dayName]]);

            if ($weekDay) {
                $weekSchedules = $weekSchedulesRepository->findBy([
                    'company' => $company->getId(),
                    'weekDay' => $weekDay->getId()
                ]);

                foreach ($weekSchedules as $schedule) {
                    $start = $schedule->getStartedAt()->format('H:i:s');
                    $end = $schedule->getEndedAt()->format('H:i:s');

                    if ($currentTime >= $start && $currentTime <= $end) {
                        return true;
                    }
                }
            }

            return false;
        }


        /**
         * @throws \Exception
         */
        #[Route('/fixture', name: 'app_fixture')]
        public function fixture(EntityManagerInterface $em): Response
        {
            $companies = [];
            $companiesName = ['Uneak', 'Colin'];
            foreach ($companiesName as $companyName) {
                $company = new Company();
                $company->setName($companyName);
                $em->persist($company);
                $companies[] = $company;
            }

            $days = [];
            $weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            foreach ($weekDays as $dayName) {
                $day = new WeekDays();
                $day->setName($dayName);
                $em->persist($day);
                $days[] = $day;
            }

            foreach ($companies as $company) {
                foreach ($days as $day) {

                    $timeSlots = [
                        ['start' => '08:00:00', 'end' => '12:00:00'],
                        ['start' => '14:00:00', 'end' => '17:00:00']
                    ];

                    foreach ($timeSlots as $slot) {
                        $weekSchedule = new WeekSchedules();
                        $weekSchedule->setWeekDay($day);
                        $weekSchedule->setStartedAt(new \DateTime($slot['start']));
                        $weekSchedule->setEndedAt(new \DateTime($slot['end']));
                        $weekSchedule->setCompany($company);
                        $em->persist($weekSchedule);
                    }
                }
            }

            $dateSchedule = new DateSchedules();
            $dateSchedule->setName("Noel exceptionnel");
            $dateSchedule->setDate(new \DateTime('2021-09-01'));
            $dateSchedule->setStartedAt(new \DateTime('08:00:00'));
            $dateSchedule->setEndedAt(new \DateTime('12:00:00'));
            $dateSchedule->setOpen(true);
            $dateSchedule->setCompany($companies[0]);
            $em->persist($dateSchedule);

            $dateSchedule = new DateSchedules();
            $dateSchedule->setName("Noel exceptionnel");
            $dateSchedule->setDate(new \DateTime('2021-09-01'));
            $dateSchedule->setStartedAt(new \DateTime('17:00:00'));
            $dateSchedule->setEndedAt(new \DateTime('22:00:00'));
            $dateSchedule->setOpen(true);
            $dateSchedule->setCompany($companies[0]);
            $em->persist($dateSchedule);

            $dateSchedule = new DateSchedules();
            $dateSchedule->setName("Ouverture pour travaux !");
            $dateSchedule->setDate(new \DateTime('2022-10-01'));
            $dateSchedule->setStartedAt(new \DateTime('8:00:00'));
            $dateSchedule->setEndedAt(new \DateTime('22:00:00'));
            $dateSchedule->setOpen(true);
            $dateSchedule->setCompany($companies[0]);
            $em->persist($dateSchedule);

            $dateSchedule = new DateSchedules();
            $dateSchedule->setName("Fermeture pour travaux !");
            $dateSchedule->setDate(new \DateTime('2022-10-01'));
            $dateSchedule->setStartedAt(new \DateTime('8:00:00'));
            $dateSchedule->setEndedAt(new \DateTime('22:00:00'));
            $dateSchedule->setOpen(false);
            $dateSchedule->setCompany($companies[1]);
            $em->persist($dateSchedule);

            $em->flush();

            return $this->redirectToRoute('app_home');
        }
    }
