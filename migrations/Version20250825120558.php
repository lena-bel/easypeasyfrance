<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825120558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE task_document (task_id INT NOT NULL, documents_id INT NOT NULL, INDEX IDX_98A9603A8DB60186 (task_id), INDEX IDX_98A9603A5F0F2752 (documents_id), PRIMARY KEY(task_id, documents_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE task_link (task_id INT NOT NULL, links_id INT NOT NULL, INDEX IDX_451F0D8A8DB60186 (task_id), INDEX IDX_451F0D8AC0DE588D (links_id), PRIMARY KEY(task_id, links_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_document ADD CONSTRAINT FK_98A9603A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_document ADD CONSTRAINT FK_98A9603A5F0F2752 FOREIGN KEY (documents_id) REFERENCES documents (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_link ADD CONSTRAINT FK_451F0D8A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_link ADD CONSTRAINT FK_451F0D8AC0DE588D FOREIGN KEY (links_id) REFERENCES links (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE task_document DROP FOREIGN KEY FK_98A9603A8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_document DROP FOREIGN KEY FK_98A9603A5F0F2752
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_link DROP FOREIGN KEY FK_451F0D8A8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task_link DROP FOREIGN KEY FK_451F0D8AC0DE588D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE task_document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE task_link
        SQL);
    }
}
