<?php

declare( strict_types=1 );

/**
 * Extension for Contao 5
 *
 * @copyright  Softleister 2020-2026
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
 */

namespace Softleister\TimetrackerBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;


class SettingsMigration extends AbstractMigration
{
    public function __construct( private readonly Connection $connection )
    {
    }


    //---------------------------------------------------------------
    //  Name der Migration (erscheint im Contao-Backend)
    //---------------------------------------------------------------
    public function getName( ): string
    {
        return 'Timetracker: tl_timetracker_setting aufteilen in tl_timetracker_kunde und tl_timetracker_task';
    }


    //---------------------------------------------------------------
    //  Bedingung: Migration nur ausführen wenn nötig
    //---------------------------------------------------------------
    public function shouldRun( ): bool
    {
        $schemaManager = $this->connection->createSchemaManager( );

        // Quelltabelle muss vorhanden sein
        if( !$schemaManager->tablesExist( ['tl_timetracker_setting'] ) ) {
            return false;
        }

        // Zieltabellen müssen bereits angelegt sein
        if( !$schemaManager->tablesExist( ['tl_timetracker_kunde', 'tl_timetracker_task'] ) ) {
            return false;
        }

        // Nur ausführen wenn tl_timetracker_kunde noch leer ist (Schutz vor Doppellauf)
        $count = $this->connection->fetchOne( 'SELECT COUNT(*) FROM tl_timetracker_kunde' );

        return (int) $count === 0;
    }


    //---------------------------------------------------------------
    //  Migration ausführen
    //---------------------------------------------------------------
    public function run( ): MigrationResult
    {
        // Kunden/Projekte migrieren
        $kunden = $this->connection->fetchAllAssociative(
            "SELECT * FROM tl_timetracker_setting WHERE type='kunde' ORDER BY id"
        );

        foreach( $kunden as $row ) {
            $this->connection->insert( 'tl_timetracker_kunde', [
                'tstamp'       => $row['tstamp'],
                'kundenname'   => $row['kundenname'],
                'kundennr'     => $row['kundennr'],
                'agentur'      => $row['agentur'],
                'stundensatz'  => $row['stundensatz'],
                'beschreibung' => $row['beschreibung'],
                'active'       => $row['active'],
                'kundenID'     => $row['kundenID'],
                'hidelist'     => $row['hidelist'],
            ] );
        }

        // Aufgaben/Dienstleistungen migrieren
        $tasks = $this->connection->fetchAllAssociative(
            "SELECT * FROM tl_timetracker_setting WHERE type='task' ORDER BY id"
        );

        foreach( $tasks as $row ) {
            $this->connection->insert( 'tl_timetracker_task', [
                'tstamp'       => $row['tstamp'],
                'aufgabe'      => $row['aufgabe'],
                'abrechnung'   => $row['abrechnung'],
                'calcstop'     => $row['calcstop'],
                'nolist'       => $row['nolist'],
                'defaultid'    => $row['defaultid'],
                'beschreibung' => $row['beschreibung'],
                'active'       => $row['active'],
                'taskID'       => $row['taskID'],
            ] );
        }

        return $this->createResult(
            true,
            sprintf( '%d Kunden und %d Aufgaben erfolgreich migriert.', count( $kunden ), count( $tasks ) )
        );
    }


    //---------------------------------------------------------------
}
