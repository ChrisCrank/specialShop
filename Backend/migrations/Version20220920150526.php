<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920150526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_category_category DROP FOREIGN KEY FK_5B3AFE912469DE2');
        $this->addSql('ALTER TABLE product_category_category DROP FOREIGN KEY FK_5B3AFE9BE6903FD');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202FBE6903FD');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202F4584665A');
        $this->addSql('DROP TABLE product_category_category');
        $this->addSql('DROP TABLE product_category_product');
        $this->addSql('ALTER TABLE product_category MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON product_category');
        $this->addSql('ALTER TABLE product_category DROP id');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('ALTER TABLE product_category ADD PRIMARY KEY (product_id, category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_category_category (product_category_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_5B3AFE9BE6903FD (product_category_id), INDEX IDX_5B3AFE912469DE2 (category_id), PRIMARY KEY(product_category_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_category_product (product_category_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_9A1E202FBE6903FD (product_category_id), INDEX IDX_9A1E202F4584665A (product_id), PRIMARY KEY(product_category_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_category_category ADD CONSTRAINT FK_5B3AFE912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_category ADD CONSTRAINT FK_5B3AFE9BE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202FBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('DROP INDEX IDX_CDFC73564584665A ON product_category');
        $this->addSql('DROP INDEX IDX_CDFC735612469DE2 ON product_category');
        $this->addSql('ALTER TABLE product_category ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
