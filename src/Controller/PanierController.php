<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Entity\Package;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/add/{id}', name: 'app_panier_add', methods: ['GET', 'POST'])]
    public function add(Request $request, Package $package, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        $panier = $user->getPanier();
        if ($panier === null) {
            $panier = new Panier();
            $user->setPanier($panier);
            $panier->setUser($user);
            $entityManager->persist($panier);
            $entityManager->persist($user);
        }

        $panier->addPackage($package);

        $entityManager->flush();

        return $this->redirectToRoute('app_panier_show', ['id' => $panier->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove/{id}', name: 'app_panier_remove', methods: ['GET', 'POST'])]
    public function remove(Request $request, Package $package, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        // dd($user);

        $panier = $user->getPanier();
        // dd($panier);
        if ($panier === null) {
            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        $panier->removePackage($package);

        $entityManager->flush();

        return $this->redirectToRoute('app_panier_show', ['id' => $panier->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        $packages = $panier->getPackage();
        $weight = 0;

        $packageJson = [
            'name' => 'my-bundle',
            'version' => '1.0.0',
            'dependencies' => [],
        ];

        foreach ($packages as $package) {
            $packageJson['dependencies'][$package->getName()] = "^".$package->getVersion();
            $weight += $package->getSize();
        }
        
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
            'packages' => $packages,
            'packageJson' => json_encode($packageJson, JSON_PRETTY_PRINT),
            'weight' => $weight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
