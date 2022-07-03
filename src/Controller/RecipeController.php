<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    const MAX_RECIPES_BY_PAGES = 10;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/recipes/{page}', name: 'all_recipes', defaults: ["page" => 1])]
    public function index(int $page = 1): Response
    {
        $offset = $this->determinesOffsetAndLimit($page);

        $recipes = $this->em->getRepository(Recipe::class)->findBy([], ['updated_at' => 'DESC'], self::MAX_RECIPES_BY_PAGES, $offset);
        if($recipes === null){
            return new Response('No recipes found', 204);
        }
        return new JsonResponse($this->serialize($recipes), Response::HTTP_OK);
    }

    /**
     * @param int $page
     * @return int
     */
    private function determinesOffsetAndLimit(int $page): int
    {
        return ($page - 1) * self::MAX_RECIPES_BY_PAGES;
    }

    /**
     * @param array<Recipe> $recipes
     * @return array
     */
    private function serialize(array $recipes): array
    {
        $data = [];
        /** @var Recipe $recipe */
        foreach ($recipes as $recipe){
            $data[] = [
                'id' => $recipe->getId(),
                'title' => $recipe->getTitle(),
                'url' => $recipe->getUrl(),
                'picture' => $recipe->getPicture() ?? $recipe->getFeed()->getPicture(),
                'updated_at' => $recipe->getUpdatedAt(),
                'description' => $recipe->getDescription(),
                'author' => $recipe->getFeed()->getAuthor(),
            ];
        }
        return $data;
    }
}
