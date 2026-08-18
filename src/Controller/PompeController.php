<?php

namespace App\Controller;

use App\Entity\Nbpompe;
use App\Entity\User;
use App\Repository\NbpompeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PompeController extends AbstractController
{
    #[Route('/pompe', name: 'app_pompe_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['countPompe']) || $data['countPompe'] === '') {
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => 'Le nombre de pompes est obligatoire.',
            ], 400);
        }

        if (!is_numeric($data['countPompe'])) {
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => 'Le nombre de pompes doit être un nombre, pas du texte.',
            ], 400);
        }

        if ((int) $data['countPompe'] <= 0) {
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => 'Le nombre de pompes doit être supérieur à 0.',
            ], 400);
        }

        $nbPompe = new Nbpompe();
        $nbPompe->setCountPompe((int) $data['countPompe']);
        $nbPompe->setDateNbpompe(new \DateTimeImmutable());
        $nbPompe->setUtilisateur($user);

        $entityManager->persist($nbPompe);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'code' => 201,
            'message' => 'Pompe créée avec succès.',
        ], 201);
    }

    #[Route('/historique', name: 'app_historique', methods: ['GET'])]
    public function historique(NbpompeRepository $nbpompeRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $seances = $nbpompeRepository->findBy(
            ['utilisateur' => $user],
            ['dateNbpompe' => 'DESC']
        );

        $totauxParJour = [];

        foreach ($seances as $nbPompe) {
            $dateSeule = $nbPompe->getDateNbpompe()->format('Y-m-d');

            if (!isset($totauxParJour[$dateSeule])) {
                $totauxParJour[$dateSeule] = 0;
            }
            $totauxParJour[$dateSeule] += $nbPompe->getCountPompe();
        }

        $historique = [];
        foreach ($totauxParJour as $date => $total) {
            $historique[] = [
                'date' => $date,
                'countPompe' => $total,
            ];
        }

        return new JsonResponse([
            'status' => 'success',
            'code' => 200,
            'historique' => $historique,
        ], 200);
    }
}