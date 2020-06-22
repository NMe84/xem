<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200622215937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Upgrades the old XEM v1 database structure to the new site\'s structure. WARNING: we can only upgrade, not downgrade. Use with caution!';
    }

    public function up(Schema $schema): void
    {
        $this->createNewTables();
        $this->migrateContent();
        $this->cleanUp();
    }

    public function down(Schema $schema): void
    {
        throw new \ErrorException('Due to the complexity of this operation we cannot downgrade to the previous version.');
    }

    private function createNewTables(): void
    {
        $this->addSql("
                 CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, password VARCHAR(100) NOT NULL,
                    activation_code VARCHAR(16) DEFAULT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL,
                    active TINYINT(1) DEFAULT '0' NOT NULL, deleted TINYINT(1) DEFAULT '0' NOT NULL, created_at DATETIME NOT NULL,
                    updated_at DATETIME DEFAULT NULL, latest_activity DATETIME DEFAULT NULL,
                    preference_email_new_accounts TINYINT(1) DEFAULT '0' NOT NULL, preference_email_new_shows TINYINT(1) DEFAULT '0' NOT NULL,
                    preference_email_public_requests TINYINT(1) DEFAULT '0' NOT NULL, INDEX IDX_8D93D649FE11D138 (Name),
                    INDEX IDX_8D93D64926535370 (Email), PRIMARY KEY(id))
                    DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ");
    }

    private function migrateContent(): void
    {
        $this->migrateUsers();
    }

    private function migrateUsers(): void
    {
        $this->addSql("
            INSERT INTO user (`id`, `name`, `email`, `password`, `activation_code`, `role`, `active`, `deleted`,
                              `created_at`, `updated_at`, `latest_activity`,
                              preference_email_new_accounts, preference_email_new_shows, preference_email_public_requests)
            SELECT user_id, user_nick, user_email, user_pass, user_activationcode,
                   CASE
                       WHEN user_lvl = 4 THEN 'ROLE_POWER_USER'
                       WHEN user_lvl = 5 THEN 'ROLE_SUPER_USER'
                       WHEN user_lvl >= 6 THEN 'ROLE_ADMIN'
                       ELSE 'ROLE_USER'
                   END, 1, 0, user_date, user_modified, user_last_login,
                   config_email_new_account, config_email_new_show, config_email_public_request
            FROM users
        ");
    }

    private function cleanUp(): void
    {
        $this->addSql("DROP TABLE users;");
    }
}
