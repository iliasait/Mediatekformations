<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Description of AdminFormationController
 *
 * @author JeMeSuisPerdu
 */
class AdminFormationController extends AbstractController
{
    private const ADMIN_FORMATIONS_TEMPLATE = "admin/formations/formations_list.html.twig";
    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }

    /**
     * @Route("/admin/formations", name="admin.formations")
     * Affiche la liste des formations avec leurs catégories.
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations/formations_list.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/formations/add", name="admin.formation.add")
     * Permet d'ajouter une nouvelle formation.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations/add', name: 'admin.formation.add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();
            $this->addFlash('success', 'Formation ajoutée avec succès !');
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/formations/formations_form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Ajouter une formation',
        ]);
    }

    /**
     * @Route("/admin/formations/{id}/edit", name="admin.formation.edit")
     * Permet de modifier une formation existante.
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations/{id}/edit', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = $this->formationRepository->find($id);
        if (!$formation) {
            throw $this->createNotFoundException('Formation introuvable.');
        }
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Formation modifiée avec succès !');
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render('admin/formations/formations_form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Modifier une formation',
        ]);
    }

    /**
     * @Route("/admin/formations/{id}/delete", name="admin.formation.delete", methods={"POST"})
     * Permet de supprimer une formation existante.
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations/{id}/delete', name: 'admin.formation.delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        if (!$formation) {
            throw $this->createNotFoundException('Formation introuvable.');
        }
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
            $this->addFlash('success', 'Formation supprimée avec succès !');
        }
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * Permet de trier les formations selon un champ et un ordre spécifiques.
     * @param string $champ
     * @param string $ordre
     * @param string $table
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_FORMATIONS_TEMPLATE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * Permet de rechercher des formations en fonction d'un champ et d'une valeur.
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_FORMATIONS_TEMPLATE, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
}
