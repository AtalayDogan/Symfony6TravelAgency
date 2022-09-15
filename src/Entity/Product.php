<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $keywords = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detail = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;



    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: ShopCart::class)]
    private Collection $shopCarts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ShopCard::class)]
    private Collection $shopCards;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->shopCarts = new ArrayCollection();
        $this->shopCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ShopCart>
     */
    public function getShopCarts(): Collection
    {
        return $this->shopCarts;
    }

    public function addShopCart(ShopCart $shopCart): self
    {
        if (!$this->shopCarts->contains($shopCart)) {
            $this->shopCarts->add($shopCart);
            $shopCart->setProductId($this);
        }

        return $this;
    }

    public function removeShopCart(ShopCart $shopCart): self
    {
        if ($this->shopCarts->removeElement($shopCart)) {
            // set the owning side to null (unless already changed)
            if ($shopCart->getProductId() === $this) {
                $shopCart->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShopCard>
     */
    public function getShopCards(): Collection
    {
        return $this->shopCards;
    }

    public function addShopCard(ShopCard $shopCard): self
    {
        if (!$this->shopCards->contains($shopCard)) {
            $this->shopCards->add($shopCard);
            $shopCard->setProduct($this);
        }

        return $this;
    }

    public function removeShopCard(ShopCard $shopCard): self
    {
        if ($this->shopCards->removeElement($shopCard)) {
            // set the owning side to null (unless already changed)
            if ($shopCard->getProduct() === $this) {
                $shopCard->setProduct(null);
            }
        }

        return $this;
    }









}
