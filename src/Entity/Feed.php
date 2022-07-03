<?php

namespace App\Entity;

use App\Repository\FeedRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
class Feed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 160)]
    private string $title;

    #[ORM\Column(type: 'string', length: 50)]
    private string $author;

    #[ORM\Column(type: 'string', length: 255)]
    private string $url;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $picture;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $updated_at;

    #[ORM\Column(type: 'string', length: 50)]
    private string $lang;

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = true;

    #[ORM\ManyToOne(targetEntity: FeedCategory::class, inversedBy: 'feeds')]
    #[ORM\JoinColumn(nullable: false)]
    private FeedCategory $category;

    #[ORM\OneToMany(mappedBy: 'feed', targetEntity: Recipe::class)]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCategory(): ?FeedCategory
    {
        return $this->category;
    }

    public function setCategory(?FeedCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Recipe>
     */
    public function getRecipes(): ArrayCollection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setFeed($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        // set the owning side to null (unless already changed)
        if ($this->recipes->removeElement($recipe) && $recipe->getFeed() === $this) {
            $recipe->setFeed(null);
        }

        return $this;
    }
}
