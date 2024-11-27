<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126190119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_schedules (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, started_at TIME NOT NULL, ended_at TIME NOT NULL, is_open TINYINT(1) NOT NULL, INDEX IDX_E4DEF3A979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week_days (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week_schedules (id INT AUTO_INCREMENT NOT NULL, week_day_id INT NOT NULL, company_id INT NOT NULL, started_at TIME NOT NULL, ended_at TIME NOT NULL, INDEX IDX_11EC168E7DB83875 (week_day_id), INDEX IDX_11EC168E979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_schedules ADD CONSTRAINT FK_E4DEF3A979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE week_schedules ADD CONSTRAINT FK_11EC168E7DB83875 FOREIGN KEY (week_day_id) REFERENCES week_days (id)');
        $this->addSql('ALTER TABLE week_schedules ADD CONSTRAINT FK_11EC168E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('DROP TABLE task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_complete TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE date_schedules DROP FOREIGN KEY FK_E4DEF3A979B1AD6');
        $this->addSql('ALTER TABLE week_schedules DROP FOREIGN KEY FK_11EC168E7DB83875');
        $this->addSql('ALTER TABLE week_schedules DROP FOREIGN KEY FK_11EC168E979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE date_schedules');
        $this->addSql('DROP TABLE week_days');
        $this->addSql('DROP TABLE week_schedules');
    }
}
