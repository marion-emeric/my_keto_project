<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629105302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feed (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(160) NOT NULL, author VARCHAR(50) NOT NULL, url VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, updated_at DATE NOT NULL, lang VARCHAR(50) NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_234044AB12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, feed_id INT NOT NULL, title VARCHAR(160) NOT NULL, url VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, updated_at DATE NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_DA88B13751A5BC03 (feed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feed ADD CONSTRAINT FK_234044AB12469DE2 FOREIGN KEY (category_id) REFERENCES feed_category (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13751A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13751A5BC03');
        $this->addSql('ALTER TABLE feed DROP FOREIGN KEY FK_234044AB12469DE2');
        $this->addSql('DROP TABLE feed');
        $this->addSql('DROP TABLE feed_category');
        $this->addSql('DROP TABLE recipe');
    }
}
