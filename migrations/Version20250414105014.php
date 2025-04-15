<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414105014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert data mandatory to start';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO month (name, slug, position) VALUES
            ('Janvier', 'janvier', 1),
            ('Février', 'fevrier', 2),
            ('Mars', 'mars', 3),
            ('Avril', 'avril', 4),
            ('Mai', 'mai', 5),
            ('Juin', 'juin', 6),
            ('Juillet', 'juillet', 7),
            ('Août', 'aout', 8),
            ('Septembre', 'septembre', 9),
            ('Octobre', 'octobre', 10),
            ('Novembre', 'novembre', 11),
            ('Décembre', 'decembre', 12)
        ");

        for ($i = 0; $i < 10; $i++) {
            $year = (int) date('Y') + $i;
            $this->addSql(sprintf('INSERT INTO year (value) VALUES (%d)', $year));
        }

        $this->addSql("INSERT INTO account_movement_type (name, slug) VALUES
            ('Restant précédent', 'restant-precedent'),
            ('Revenus', 'revenus'),
            ('Dépenses fixes', 'depenses-fixes'),
            ('Dépenses', 'depenses')
        ");

        $this->addSql("INSERT INTO account_type (name, slug) VALUES
            ('Courant', 'courant'),
            ('Commun', 'commun')
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM month WHERE position BETWEEN 1 AND 12");
        $this->addSql("DELETE FROM year WHERE value BETWEEN " . date('Y') . " AND " . (date('Y') + 9));
        $this->addSql("DELETE FROM account_movement_type WHERE slug IN ('restant-precedent', 'revenus', 'depenses-fixes', 'depenses')");
        $this->addSql("DELETE FROM account_type WHERE slug IN ('courant', 'commun')");
    }
}
