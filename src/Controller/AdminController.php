<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PackageRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(PackageRepository $packageRepository, CategoryRepository $categoryRepository, UserRepository $userRepository): Response
    {
        if (!$this->getUser()->getRoles() == ['ROLE_ADMIN']) {
            return $this->redirectToRoute('index');
        }

        $packages = $packageRepository->findAll();
        $categories = $categoryRepository->findAllCategories();
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
            'packages' => $packages,
            'users' => $users,
            'controller_name' => 'AdminController',
        ]);
    }

}
