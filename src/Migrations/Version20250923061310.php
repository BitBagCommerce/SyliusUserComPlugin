<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Migrations;

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
