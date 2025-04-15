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
        $this->addSql("INSERT INTO month (name, position) VALUES
            ('Janvier', 1),
            ('Février', 2),
            ('Mars', 3),
            ('Avril', 4),
            ('Mai', 5),
            ('Juin', 6),
            ('Juillet', 7),
            ('Août', 8),
            ('Septembre', 9),
            ('Octobre', 10),
            ('Novembre', 11),
            ('Décembre', 12)
        ");

        for ($i = 0; $i < 10; $i++) {
            $year = (int) date('Y') + $i;
            $this->addSql("INSERT INTO year (value) VALUES $year");
        }

        $this->addSql("INSERT INTO account_movement_type (name) VALUES
            ('Restant précédent'),
            ('Revenus'),
            ('Dépenses fixes'),
            ('Dépenses')
        ");

        $this->addSql("INSERT INTO account_type (name) VALUES
            ('Courant'),
            ('Commun')
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM month WHERE position BETWEEN 1 AND 12");
        $this->addSql("DELETE FROM year WHERE value BETWEEN " . date('Y') . " AND " . (date('Y') + 9));
        $this->addSql("DELETE FROM account_movement_type WHERE name IN ('Restant précédent', 'Revenus', 'Dépenses fixes', 'Dépenses')");
        $this->addSql("DELETE FROM account_type WHERE name IN ('Courant', 'Commun')");
    }
}
