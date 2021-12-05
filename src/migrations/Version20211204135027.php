<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204135027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADD17F50A6 ON product (uuid)');
        $this->addSql('ALTER TABLE product_category ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDFC7356D17F50A6 ON product_category (uuid)');
        $this->addSql('ALTER TABLE product_property ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_40427649D17F50A6 ON product_property (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D34A04ADD17F50A6 ON product');
        $this->addSql('ALTER TABLE product DROP uuid');
        $this->addSql('DROP INDEX UNIQ_CDFC7356D17F50A6 ON product_category');
        $this->addSql('ALTER TABLE product_category DROP uuid');
        $this->addSql('DROP INDEX UNIQ_40427649D17F50A6 ON product_property');
        $this->addSql('ALTER TABLE product_property DROP uuid');
    }
}
