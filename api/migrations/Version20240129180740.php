<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129180740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id UUID NOT NULL, post_id UUID NOT NULL, system_name VARCHAR(500) NOT NULL, description VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_795FD9BB4B89032C ON attachment (post_id)');
        $this->addSql('COMMENT ON COLUMN attachment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attachment.post_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE post (id UUID NOT NULL, text_content VARCHAR(1000) DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BB4B89032C');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE post');
    }
}
