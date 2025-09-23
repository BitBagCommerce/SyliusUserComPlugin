<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250923061310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User.com integration fields to Sylius Channel';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_channel ADD user_com_url VARCHAR(255) DEFAULT NULL, ADD user_com_api_key VARCHAR(255) DEFAULT NULL, ADD user_com_gtm_container_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_channel DROP user_com_url, DROP user_com_api_key, DROP user_com_gtm_container_id');
    }
}
