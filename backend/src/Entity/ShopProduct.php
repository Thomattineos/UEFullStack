<?php

namespace App\Entity;

use App\Repository\ShopProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopProductRepository::class)
 */
class ShopProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="shopProducts")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    private $shop;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="shopProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->shop = new ArrayCollection();
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShop(): Collection
    {
        return $this->shop;
    }

    public function addShop(Shop $shop): self
    {
        if (!$this->shop->contains($shop)) {
            $this->shop[] = $shop;
            $shop->setProducts($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): self
    {
        if ($this->shop->removeElement($shop)) {
            // set the owning side to null (unless already changed)
            if ($shop->getProducts() === $this) {
                $shop->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setShops($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getShops() === $this) {
                $product->setShops(null);
            }
        }

        return $this;
    }
}
