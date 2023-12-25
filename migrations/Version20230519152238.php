<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519152238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AF8697D13');
        $this->addSql('DROP INDEX IDX_E01FBE6AF8697D13 ON images');
        $this->addSql('ALTER TABLE images CHANGE comment_id discussion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A1ADED311 FOREIGN KEY (discussion_id) REFERENCES discussions (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A1ADED311 ON images (discussion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A1ADED311');
        $this->addSql('DROP INDEX IDX_E01FBE6A1ADED311 ON images');
        $this->addSql('ALTER TABLE images CHANGE discussion_id comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AF8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6AF8697D13 ON images (comment_id)');
    }
}
