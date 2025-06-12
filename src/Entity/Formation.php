namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    private const CHEMIN_IMAGE = "https://i.ytimg.com/vi/";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\LessThanOrEqual(value: "today", message: "La date de publication ne peut pas être dans le futur.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: "formation", targetEntity: Certif::class, cascade: ["persist", "remove"])]
    private Collection $certifications;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certif $certif): static
    {
        if (!$this->certifications->contains($certif)) {
            $this->certifications->add($certif);
            $certif->setFormation($this);
        }

        return $this;
    }

    public function removeCertification(Certif $certif): static
    {
        if ($this->certifications->removeElement($certif)) {
            if ($certif->getFormation() === $this) {
                $certif->setFormation(null);
            }
        }

        return $this;
    }
}

