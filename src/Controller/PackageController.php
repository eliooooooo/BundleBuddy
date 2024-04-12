<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Michelf\Markdown;
use App\Entity\Package;
use App\Form\PackageType;
use App\Repository\PackageRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/package')]
class PackageController extends AbstractController
{
    #[Route('/', name: 'app_package_index', methods: ['GET'])]
    public function index(PackageRepository $packageRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $display = $request->query->get('display');
        $display = $display ? $display : 'vignette';

        $selectedCategory = $request->query->get('category');
        $selectedCategory = $selectedCategory ? $selectedCategory : 'all';
        $selectedLanguage = $request->query->get('language');
        $selectedLanguage = $selectedLanguage ? $selectedLanguage : 'all';

        $categories = $categoryRepository->findAllCategories();
        $languages = $packageRepository->findAllLanguages();

        if ($selectedCategory == 'all' && $languages == 'all') {
            $packages = $packageRepository->findAll();
        } else {
            $packages = $packageRepository->getPackageByFilters($selectedCategory, $selectedLanguage);
        }
        
        return $this->render('package/index.html.twig', [
            'packages' => $packages,
            'languages' => $languages,
            'display' => $display,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'selectedLanguage' => $selectedLanguage
        ]);
    }

    #[Route('/new', name: 'app_package_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $package = new Package();
        $form = $this->createForm(PackageType::class, $package);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
        
            if ($image) {
                $filename = 'image-'.$package->getId().'.'.$image->guessExtension();
                
                // Déplacer l'image avant de l'enregistrer dans l'entité
                $image->move('uploads', $filename);
    
                // Enregistrer le nom du fichier dans l'entité
                $package->setImage($filename);
    
                $entityManager->persist($package);
                $entityManager->flush();
            }
            // Enregistrement final (si aucun fichier n'est envoyé ou pour mettre à jour le nom du ficher)
            $entityManager->persist($package);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_package_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('package/new.html.twig', [
            'package' => $package,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_package_show', methods: ['GET'])]
    public function show(Package $package): Response
    {
        $client = new Client();

        $url = $package->getRepository();
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];
        $segments = explode('/', $path);

        $user = $segments[1];
        $repo = $segments[2];

        $response = $client->request('GET', 'https://raw.githubusercontent.com/' . $user . '/' . $repo . '/main/README.md');
    
        $markdown = $response->getBody()->getContents();
        $html = Markdown::defaultTransform($markdown);
        $html = preg_replace('/src="(\.\/[^"]*)"/', 'src="https://raw.githubusercontent.com/' . $user . '/' . $repo . '/main/$1"', $html);
        $html = preg_replace('/srcset="(\.\/[^"]*)"/', 'src="https://raw.githubusercontent.com/' . $user . '/' . $repo . '/main/$1"', $html);

        return $this->render('package/show.html.twig', [
            'package' => $package,
            'readme' => $html,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_package_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Package $package, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PackageType::class, $package);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
        
            if ($image) {
                // Supprimer l'ancienne image si elle existe
                $oldImage = $package->getImage();
                if ($oldImage && file_exists('uploads/' . $oldImage)) {
                    unlink('uploads/' . $oldImage);
                }
    
                // Télécharger la nouvelle image
                $filename = 'image-'.$package->getId().'.'.$image->guessExtension();
                $image->move('uploads', $filename);
                $package->setImage($filename);
            }
    
            $entityManager->persist($package);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_package_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('package/edit.html.twig', [
            'package' => $package,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_package_delete', methods: ['POST'])]
    public function delete(Request $request, Package $package, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$package->getId(), $request->request->get('_token'))) {
            if (file_exists('uploads/' . $package->getImage()))
                unlink('uploads/' . $package->getImage());
            $entityManager->remove($package);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_package_index', [], Response::HTTP_SEE_OTHER);
    }
}
