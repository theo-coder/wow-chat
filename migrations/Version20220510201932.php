<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510201932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ALTER authorized_roles TYPE JSON USING (authorized_roles::json)');
        $this->addSql('ALTER TABLE category ALTER authorized_roles DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN category.authorized_roles IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ALTER authorized_roles TYPE TEXT');
        $this->addSql('ALTER TABLE category ALTER authorized_roles DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN category.authorized_roles IS \'(DC2Type:array)\'');
    }
}
