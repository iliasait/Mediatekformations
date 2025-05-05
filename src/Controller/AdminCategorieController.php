<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminCategorieController extends AbstractController
{
    /**
     * @Route("/admin/categories/", name="admin.categories")
     * Permet d'afficher la liste des catégories et d'ajouter une nouvelle catégorie.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategorieRepository $repository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories/', name: 'admin.categories')]
    public function index(Request $request, EntityManagerInterface $entityManager, CategorieRepository $repository): Response
    {
        // Initialisation du formulaire
        $category = new Categorie();
        $form = $this->createForm(CategorieType::class, $category);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $existingCategory = $repository->findOneBy(['name' => $category->getName()]);
    
            if ($existingCategory) {
                $this->addFlash('error', 'Cette catégorie existe déjà.');
            } else {
                $entityManager->persist($category);
                $entityManager->flush();
                $this->addFlash('success', 'Catégorie ajoutée avec succès.');
            }
    
            return $this->redirectToRoute('admin.categories');
        }
    
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $repository->findAll(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/delete/{id}", name="admin.categories.delete", methods={"POST"})
     * Permet de supprimer une catégorie existante.
     * @param int $id
     * @param CategorieRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/delete/{id}', name: 'admin.categories.delete', methods: ['POST'])]
    public function delete(int $id, CategorieRepository $repository, EntityManagerInterface $entityManager,Request $request): Response
    {
        $category = $repository->find($id);
        if (!$category) {
            $this->addFlash('error', 'Catégorie introuvable.');
        } elseif ($repository->isCategoryUsed($id)) {
            $this->addFlash('error', 'Impossible de supprimer cette catégorie, elle est utilisée.');
        } elseif($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token')))
        {
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        }
        return $this->redirectToRoute('admin.categories');
    }
}
