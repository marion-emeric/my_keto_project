<?php

namespace App\Command;

use App\Entity\Feed;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Eko\FeedBundle\Feed\Reader;
use Eko\FeedBundle\Hydrator\DefaultHydrator;
use FeedHydrator;
use PhpParser\Error;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'generate:feeds',
    description: 'This command generates feeds.',
)]
class GenerateFeedsCommand extends Command
{
    /**
     * @var Reader
     */
    private Reader $reader;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, Reader $reader, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->reader = $reader;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command generates feeds.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Feeds generating...');

        // Get All Feeds
        $allFeeds = $this->entityManager->getRepository(Feed::class)->findAll();

        $this->reader->setHydrator(new FeedHydrator());
        /** @var Feed $feedEntity */
        foreach ($allFeeds as $feedEntity) {
            // Check if Feed is up-to-date
            if ($this->feedIsUpToDate($feedEntity) === false) {
                try {
                    if ($feedEntity->getCategory()->getId() === 1) {
                        $items = $this->reader->load($feedEntity->getUrl())->populate(Recipe::class);
                        /** @var Recipe $item */
                        foreach ($items as $item) {
                            // TODO: How add picture element?
                            $item->setFeed($feedEntity);
                            $this->entityManager->persist($item);
                        }
                    } elseif ($feedEntity->getCategory()->getName() === 2) {
                        // TODO: do the same with Actuality entity
                    }
                } catch (Error $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    /**
     * @param Feed $feedEntity
     * @return bool
     */
    private function feedIsUpToDate(Feed $feedEntity): bool
    {
        /** @var \DateTime $feedUpdatedDate */
        $feedUpdatedDate = $this->reader->load($feedEntity->getUrl())->get()->getDateModified();
        return $feedEntity->getUpdatedAt()->format('Y-m-d') === $feedUpdatedDate->format('Y-m-d');
    }
}
