<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160408150908 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accessprofile (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, is_weekend TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8AEFD8BE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billingclass (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, coeff NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_CF1C57435E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, billingclass_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4FBF094F5E237E06 (name), INDEX IDX_4FBF094FF56661BA (billingclass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slots LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX UNIQ_D338D5835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cof_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, role VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_547BA51657698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cof_users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(25) NOT NULL, last_name VARCHAR(25) NOT NULL, phone_number VARCHAR(25) NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4D338AD1F85E0677 (username), UNIQUE INDEX UNIQ_4D338AD1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_company (user_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_17B21745A76ED395 (user_id), INDEX IDX_17B21745979B1AD6 (company_id), PRIMARY KEY(user_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_equipment (id INT AUTO_INCREMENT NOT NULL, accessprofile_id INT DEFAULT NULL, user_id INT DEFAULT NULL, equipment_id INT DEFAULT NULL, INDEX IDX_D3D85867D0D5A65E (accessprofile_id), INDEX IDX_D3D85867A76ED395 (user_id), INDEX IDX_D3D85867517FE9FE (equipment_id), UNIQUE INDEX UNIQ_D3D85867A76ED395517FE9FE (user_id, equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF56661BA FOREIGN KEY (billingclass_id) REFERENCES billingclass (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES cof_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES cof_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B21745A76ED395 FOREIGN KEY (user_id) REFERENCES cof_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B21745979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_equipment ADD CONSTRAINT FK_D3D85867D0D5A65E FOREIGN KEY (accessprofile_id) REFERENCES accessprofile (id)');
        $this->addSql('ALTER TABLE user_equipment ADD CONSTRAINT FK_D3D85867A76ED395 FOREIGN KEY (user_id) REFERENCES cof_users (id)');
        $this->addSql('ALTER TABLE user_equipment ADD CONSTRAINT FK_D3D85867517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_equipment DROP FOREIGN KEY FK_D3D85867D0D5A65E');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF56661BA');
        $this->addSql('ALTER TABLE user_company DROP FOREIGN KEY FK_17B21745979B1AD6');
        $this->addSql('ALTER TABLE user_equipment DROP FOREIGN KEY FK_D3D85867517FE9FE');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_company DROP FOREIGN KEY FK_17B21745A76ED395');
        $this->addSql('ALTER TABLE user_equipment DROP FOREIGN KEY FK_D3D85867A76ED395');
        $this->addSql('DROP TABLE accessprofile');
        $this->addSql('DROP TABLE billingclass');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE cof_role');
        $this->addSql('DROP TABLE cof_users');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('DROP TABLE user_equipment');
    }
}
