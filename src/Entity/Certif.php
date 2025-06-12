namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Certif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nomCertif;

    #[ORM\Column(type: "date")]
    private \DateTime $dateObtention;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: "certifications")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;
}
