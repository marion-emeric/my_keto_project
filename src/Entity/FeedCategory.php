<?php

namespace App\Entity;

use App\Repository\FeedCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedCategoryRepository::class)]
class FeedCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Feed::class)]
    private $feeds;

    public function __construct()
    {
        $this->feeds = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Feed>
     */
    public function getFeeds(): Collection
    {
        return $this->feeds;
    }

    public function addFeed(Feed $feed): self
    {
        if (!$this->feeds->contains($feed)) {
            $this->feeds[] = $feed;
            $feed->setCategory($this);
        }

        return $this;
    }

    public function removeFeed(Feed $feed): self
    {
        // set the owning side to null (unless already changed)
        if ($this->feeds->removeElement($feed) && $feed->getCategory() === $this) {
            $feed->setCategory(null);
        }

        return $this;
    }
}
