<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\Tests\Stubs\Migration;
use sonrac\FCoverage\Tests\Stubs\MigrationWithSeeds;

/**
 * Class MigrationTraitTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class MigrationTraitTest extends TestCase
{
    /**
     * Test boot trait.
     *
     * @throws \ReflectionException
     *
     * @return \sonrac\FCoverage\Tests\Stubs\Migration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testBoot()
    {
        $migration = new Migration();

        static::assertFileExists('/tmp/1.txt');
        static::assertEquals("123\n", file_get_contents('/tmp/1.txt'));

        unlink('/tmp/1.txt');

        return $migration;
    }

    /**
     * Test rollback migration.
     *
     * @throws \ReflectionException
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRollback()
    {
        $migration = new MigrationWithSeeds();
        $migration = $migration
            ->setRollbackMigrationCommand('123 > ')
            ->setBinDir(__DIR__);

        $migration->rollback();

        foreach ([1, 2, 3] as $item) {
            $this->assertFileExists(__DIR__.'/'.$item);
            $this->assertEquals("123\n", file_get_contents(__DIR__.'/'.$item));
            unlink(__DIR__.'/'.$item);
        }

        $migration = new Migration();
        $this->assertFalse($migration->rollback());
    }

    /**
     * Test boot with seeds.
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testBootWithSeeds()
    {
        new MigrationWithSeeds();

        foreach ([1, 2, 3] as $item) {
            $this->assertFileExists(__DIR__.'/'.$item);
            $this->assertEquals("123\n", file_get_contents(__DIR__.'/'.$item));
            unlink(__DIR__.'/'.$item);
        }
    }
}
