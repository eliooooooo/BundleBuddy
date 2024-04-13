<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PackageRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(PackageRepository $packageRepository, CategoryRepository $categoryRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()->getRoles() == ['ROLE_ADMIN']) {
            return $this->redirectToRoute('index');
        }

        $dql = "SELECT p, COUNT(b) as bundle_count
                FROM App\Entity\Package p
                LEFT JOIN p.paniers b
                GROUP BY p";
        $query = $entityManager->createQuery($dql);

        $packagesWithCount = $query->getResult();

        $categories = $categoryRepository->findAllCategories();
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
            'packages' => $packagesWithCount,
            'users' => $users,
            'controller_name' => 'AdminController',
        ]);
    }
}
