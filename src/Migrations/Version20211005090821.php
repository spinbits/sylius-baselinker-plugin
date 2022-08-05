<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005090821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Extend order entity with baslinker_order_id column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `sylius_order` ADD `baselinker_order_id` VARCHAR(32) DEFAULT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `sylius_order` DROP `baselinker_order_id`');
    }
}
