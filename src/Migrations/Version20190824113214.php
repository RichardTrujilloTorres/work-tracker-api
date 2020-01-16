<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824113214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE commit ADD entry_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commit ADD CONSTRAINT FK_4ED42EADBA364942 ' .
            'FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('CREATE INDEX IDX_4ED42EADBA364942 ON commit (entry_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE commit DROP FOREIGN KEY FK_4ED42EADBA364942');
        $this->addSql('DROP INDEX IDX_4ED42EADBA364942 ON commit');
        $this->addSql('ALTER TABLE commit DROP entry_id');
    }
}
