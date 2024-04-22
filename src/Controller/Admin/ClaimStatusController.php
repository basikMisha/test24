<?php

namespace App\Controller\Admin;

use App\Entity\ClaimStatus;
use App\Form\ClaimStatusType;
use App\Repository\ClaimStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/claimstatus')]
class ClaimStatusController extends AbstractController
{
    #[Route('/', name: 'app_claimstatus_index', methods: ['GET'])]
    public function index(ClaimStatusRepository $claimStatusRepository): Response
    {
        return $this->render('claimstatus/index.html.twig', [
            'claimStatuses' => $claimStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_claimstatus_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $claimStatus = new ClaimStatus();
        $form = $this->createForm(ClaimStatusType::class, $claimStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($claimStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_claimstatus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claimstatus/new.html.twig', [
            'claimStatus' => $claimStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_claimstatus_show', methods: ['GET'])]
    public function show(ClaimStatus $claimStatus): Response
    {
        return $this->render('claimstatus/show.html.twig', [
            'claimStatus' => $claimStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_claimstatus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClaimStatus $claimStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClaimStatusType::class, $claimStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_claimstatus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claimstatus/edit.html.twig', [
            'claimStatus' => $claimStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_claimstatus_delete', methods: ['POST'])]
    public function delete(Request $request, ClaimStatus $claimStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$claimStatus->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($claimStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_claimstatus_index', [], Response::HTTP_SEE_OTHER);
    }
}
