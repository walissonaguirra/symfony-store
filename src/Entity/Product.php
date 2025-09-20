<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Esta campo é obrigatório!')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Esta campo é obrigatório!')]
    #[Length(min: 30, minMessage: 'A descrição deve ter no minimo 30 caracteries!')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank(message: 'Esta campo é obrigatório!')]
    private ?string $bady = null;

    #[ORM\Column]
    #[NotBlank(message: 'Esta campo é obrigatório!')]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Esta campo é obrigatório!')]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    /**
     * @var Collection<int, ProductPhoto>
     */
    #[ORM\OneToMany(targetEntity: ProductPhoto::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $productPhotos;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBady(): ?string
    {
        return $this->bady;
    }

    public function setBady(string $bady): static
    {
        $this->bady = $bady;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo'));

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo'));

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, ProductPhoto>
     */
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }

    public function addProductPhoto(ProductPhoto $productPhoto): static
    {
        if (!$this->productPhotos->contains($productPhoto)) {
            $this->productPhotos->add($productPhoto);
            $productPhoto->setProduct($this);
        }

        return $this;
    }

    public function removeProductPhoto(ProductPhoto $productPhoto): static
    {
        if ($this->productPhotos->removeElement($productPhoto)) {
            // set the owning side to null (unless already changed)
            if ($productPhoto->getProduct() === $this) {
                $productPhoto->setProduct(null);
            }
        }

        return $this;
    }
}
