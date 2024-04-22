<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240422105922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE claim (id INT AUTO_INCREMENT NOT NULL, claimstatus_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_A769DE2784C7A399 (claimstatus_id), INDEX IDX_A769DE27A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_to_claim (claim_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_B1118C117096A49F (claim_id), INDEX IDX_B1118C11F8697D13 (comment_id), PRIMARY KEY(claim_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE claim_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE2784C7A399 FOREIGN KEY (claimstatus_id) REFERENCES claim_status (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_to_claim ADD CONSTRAINT FK_B1118C117096A49F FOREIGN KEY (claim_id) REFERENCES claim (id)');
        $this->addSql('ALTER TABLE comment_to_claim ADD CONSTRAINT FK_B1118C11F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE2784C7A399');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27A76ED395');
        $this->addSql('ALTER TABLE comment_to_claim DROP FOREIGN KEY FK_B1118C117096A49F');
        $this->addSql('ALTER TABLE comment_to_claim DROP FOREIGN KEY FK_B1118C11F8697D13');
        $this->addSql('DROP TABLE claim');
        $this->addSql('DROP TABLE comment_to_claim');
        $this->addSql('DROP TABLE claim_status');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
