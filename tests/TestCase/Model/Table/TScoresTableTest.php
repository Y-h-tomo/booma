<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TScoresTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TScoresTable Test Case
 */
class TScoresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TScoresTable
     */
    protected $TScores;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.TScores',
        'app.MUsers',
        'app.TBooks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TScores') ? [] : ['className' => TScoresTable::class];
        $this->TScores = $this->getTableLocator()->get('TScores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TScores);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
