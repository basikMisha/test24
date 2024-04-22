<?php

namespace App\Controller;

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

#[Route('/user/claims')]
class ClaimController extends AbstractController
{
    #[Route('/', name: 'app_user_claims_index', methods: ['GET'])]
    public function index(Request $request, ClaimRepository $claimRepository): Response
    {
        $claimFilter = new ClaimFilter($this->getUser());

        $form = $this->createForm(ClaimFilterType::class, $claimFilter);
        $form->handleRequest($request);

        return $this->render('claims/index.html.twig', [
            'claims' => $claimRepository->findByCommentFilter($claimFilter),
            'searchForm' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_user_claims_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $claim = new Claim($this->getUser());
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($claim);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_claims_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claims/new.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }
    #[IsGranted('edit', 'claim', 'Post not found', 404)]
    #[Route('/{id}/edit', name: 'app_user_claims_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_claims_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_user_claims_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('claims/edit.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

    #[IsGranted('edit', 'claim', 'Claim not found', 404)]
    #[Route('/{id}', name: 'app_user_claims_delete', methods: ['POST'])]
    public function delete(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$claim->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($claim);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_claims_index', [], Response::HTTP_SEE_OTHER);
    }
}
