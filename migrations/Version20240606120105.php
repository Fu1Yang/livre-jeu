<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606120105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternative ADD etape_precedente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alternative ADD CONSTRAINT FK_EFF5DFA3F94EAC8 FOREIGN KEY (etape_precedente_id) REFERENCES etape (id)');
        $this->addSql('CREATE INDEX IDX_EFF5DFA3F94EAC8 ON alternative (etape_precedente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternative DROP FOREIGN KEY FK_EFF5DFA3F94EAC8');
        $this->addSql('DROP INDEX IDX_EFF5DFA3F94EAC8 ON alternative');
        $this->addSql('ALTER TABLE alternative DROP etape_precedente_id');
    }
}
