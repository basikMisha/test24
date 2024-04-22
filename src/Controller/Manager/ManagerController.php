<?php

namespace App\Controller\Manager;

use App\Entity\Claim;
use App\Filter\ClaimFilter;
use App\Form\ClaimFilterType;
use App\Form\ClaimType;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/manager/claims')]
class ManagerController extends AbstractController
{
    #[Route('/', name: 'app_manager_claims_index', methods: ['GET'])]
    public function index(Request $request, ClaimRepository $claimRepository): Response
    {
        $claimFilter = new ClaimFilter();

        $form = $this->createForm(ClaimFilterType::class, $claimFilter);
        $form->handleRequest($request);

        return $this->render('claims/index.html.twig', [
            'claims' => $claimRepository->findByCommentFilter($claimFilter),
            'searchForm' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_manager_claims_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $claim = new Claim($this->getUser());
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($claim);
            $entityManager->flush();

            return $this->redirectToRoute('app_manager_claims_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claims/new.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_manager_claims_show', methods: ['GET'])]
    public function show(Claim $claim): Response
    {
        return $this->render('claims/show.html.twig', [
            'claim' => $claim,
        ]);
    }

    #[IsGranted('edit', 'claim', 'Post not found', 404)]
    #[Route('/{id}/edit', name: 'app_manager_claims_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_manager_claims_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claims/edit.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }
}
