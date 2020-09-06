<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200906111556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE money (id INT AUTO_INCREMENT NOT NULL, vending_machine_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_B7DF13E482EA3E1C (vending_machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vending_machine (id INT AUTO_INCREMENT NOT NULL, actual_money_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_FC90FCE7376F180C (actual_money_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, vending_machine_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04AD82EA3E1C (vending_machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE money ADD CONSTRAINT FK_B7DF13E482EA3E1C FOREIGN KEY (vending_machine_id) REFERENCES vending_machine (id)');
        $this->addSql('ALTER TABLE vending_machine ADD CONSTRAINT FK_FC90FCE7376F180C FOREIGN KEY (actual_money_id) REFERENCES money (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD82EA3E1C FOREIGN KEY (vending_machine_id) REFERENCES vending_machine (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vending_machine DROP FOREIGN KEY FK_FC90FCE7376F180C');
        $this->addSql('ALTER TABLE money DROP FOREIGN KEY FK_B7DF13E482EA3E1C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD82EA3E1C');
        $this->addSql('DROP TABLE money');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE vending_machine');
    }
}
