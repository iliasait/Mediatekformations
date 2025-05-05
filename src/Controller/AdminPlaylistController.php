<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Description of AdminPlaylistController
 *
 * @author JeMeSuisPerdu
 */
class AdminPlaylistController extends AbstractController {
    private const ADMIN_PLAYLISTS_TEMPLATE = "admin/playlists/playlists_list.html.twig";
    
    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    public function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * Affiche la liste des playlists avec la possibilité de les trier par le nombre de formations.
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(Request $request): Response {
        $sortBy = $request->query->get('sortBy', 'ASC');
        $playlists = $this->playlistRepository->findAllOrderByFormationsCount($sortBy);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TEMPLATE, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/add", name="admin.playlists.add", methods={"GET", "POST"})
     * Permet d'ajouter une nouvelle playlist.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists/add', name: 'admin.playlists.add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();
            $this->addFlash('success', 'Playlist ajoutée avec succès !');
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render('admin/playlists/playlists_form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/playlists/{id}/edit", name="admin.playlists.edit", methods={"GET", "POST"})
     * Permet d'éditer une playlist existante.
     * @param Request $request
     * @param Playlist $playlist
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists/{id}/edit', name: 'admin.playlists.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render('admin/playlists/playlists_form_edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/playlists/{id}/delete", name="admin.playlists.delete", methods={"POST"})
     * Permet de supprimer une playlist existante.
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists/{id}/delete', name: 'admin.playlists.delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        if (!$playlist) {
            throw $this->createNotFoundException('Playlist introuvable.');
        }
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playlist);
            $entityManager->flush();
            $this->addFlash('success', 'Playlist supprimée avec succès !');
        }
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * Permet de trier les playlists selon un champ spécifique et un ordre donné.
     * @param string $champ
     * @param string $ordre
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        if ($champ === "name" || $champ === "description") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } else {
            $playlists = $this->playlistRepository->findAll();
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TEMPLATE, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * Permet de rechercher des playlists en fonction d'un champ et d'une valeur donnée.
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMIN_PLAYLISTS_TEMPLATE, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
}
