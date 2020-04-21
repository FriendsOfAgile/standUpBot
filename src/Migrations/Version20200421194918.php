<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200421194918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE carma_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE space_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stand_up_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stand_up_config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE carma (id INT NOT NULL, user_id INT NOT NULL, author_id INT NOT NULL, is_positive BOOLEAN NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_26B992FCA76ED395 ON carma (user_id)');
        $this->addSql('CREATE INDEX IDX_26B992FCF675F31B ON carma (author_id)');
        $this->addSql('CREATE TABLE space (id INT NOT NULL, type VARCHAR(100) NOT NULL, name VARCHAR(255) NOT NULL, uid VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, config_id INT NOT NULL, text VARCHAR(500) NOT NULL, color VARCHAR(100) NOT NULL, sort INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E24DB0683 ON question (config_id)');
        $this->addSql('CREATE TABLE stand_up (id INT NOT NULL, user_id INT NOT NULL, config_id INT NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B995DA7A76ED395 ON stand_up (user_id)');
        $this->addSql('CREATE INDEX IDX_6B995DA724DB0683 ON stand_up (config_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, space_id INT DEFAULT NULL, uid VARCHAR(500) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, time_zone VARCHAR(100) DEFAULT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D64923575340 ON "user" (space_id)');
        $this->addSql('CREATE TABLE member (id INT NOT NULL, config_id INT NOT NULL, user_id INT NOT NULL, can_read BOOLEAN NOT NULL, can_write BOOLEAN NOT NULL, can_edit BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70E4FA7824DB0683 ON member (config_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78A76ED395 ON member (user_id)');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, config_id INT NOT NULL, days_of_the_week JSON DEFAULT NULL, weeks_for_repeaat INT NOT NULL, use_user_timezone BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A3811FB24DB0683 ON schedule (config_id)');
        $this->addSql('CREATE TABLE stand_up_config (id INT NOT NULL, space_id INT NOT NULL, author_id INT NOT NULL, message_before VARCHAR(500) DEFAULT NULL, message_after VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F940B5523575340 ON stand_up_config (space_id)');
        $this->addSql('CREATE INDEX IDX_2F940B55F675F31B ON stand_up_config (author_id)');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, question VARCHAR(500) NOT NULL, color VARCHAR(100) DEFAULT NULL, answer VARCHAR(500) NOT NULL, sort INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE carma ADD CONSTRAINT FK_26B992FCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carma ADD CONSTRAINT FK_26B992FCF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E24DB0683 FOREIGN KEY (config_id) REFERENCES stand_up_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stand_up ADD CONSTRAINT FK_6B995DA7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stand_up ADD CONSTRAINT FK_6B995DA724DB0683 FOREIGN KEY (config_id) REFERENCES stand_up_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64923575340 FOREIGN KEY (space_id) REFERENCES space (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA7824DB0683 FOREIGN KEY (config_id) REFERENCES stand_up_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB24DB0683 FOREIGN KEY (config_id) REFERENCES stand_up_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stand_up_config ADD CONSTRAINT FK_2F940B5523575340 FOREIGN KEY (space_id) REFERENCES space (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stand_up_config ADD CONSTRAINT FK_2F940B55F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64923575340');
        $this->addSql('ALTER TABLE stand_up_config DROP CONSTRAINT FK_2F940B5523575340');
        $this->addSql('ALTER TABLE carma DROP CONSTRAINT FK_26B992FCA76ED395');
        $this->addSql('ALTER TABLE carma DROP CONSTRAINT FK_26B992FCF675F31B');
        $this->addSql('ALTER TABLE stand_up DROP CONSTRAINT FK_6B995DA7A76ED395');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA78A76ED395');
        $this->addSql('ALTER TABLE stand_up_config DROP CONSTRAINT FK_2F940B55F675F31B');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E24DB0683');
        $this->addSql('ALTER TABLE stand_up DROP CONSTRAINT FK_6B995DA724DB0683');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA7824DB0683');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB24DB0683');
        $this->addSql('DROP SEQUENCE carma_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE space_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stand_up_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stand_up_config_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP TABLE carma');
        $this->addSql('DROP TABLE space');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE stand_up');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE stand_up_config');
        $this->addSql('DROP TABLE answer');
    }
}
