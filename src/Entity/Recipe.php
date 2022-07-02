<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Reader\ItemInterface;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe implements ItemInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 160)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $url;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $picture;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $updated_at;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Feed::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Feed $feed;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    /**
     * @param $title
     * @return Recipe
     */
    public function setFeedItemTitle($title): static
    {
        $this->setTitle($title);
        return $this;
    }

    /**
     * @param $description
     * @return Recipe
     */
    public function setFeedItemDescription($description): static
    {
        $this->setDescription($description);
        return $this;
    }

    /**
     * @param $link
     * @return Recipe
     */
    public function setFeedItemLink($link): static
    {
        $this->setUrl($link);
        return $this;
    }

    /**
     * @param \DateTime $date
     * @return Recipe
     */
    public function setFeedItemPubDate(DateTime $date): static
    {
        $this->setUpdatedAt($date);
        return $this;
    }
}
