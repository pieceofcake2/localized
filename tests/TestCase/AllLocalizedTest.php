<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         Localized Plugin v 0.3
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;

/**
 * AllLocalizedTest class
 *
 * This test group will run all tests.
 */
class AllLocalizedTest extends TestSuite
{
    /**
     * Suite define the tests for this suite
     *
     * @return Test
     */
    public static function suite(): Test
    {
        $suite = new CakeTestSuite('All Tests');
        $suite->addTestDirectoryRecursive(CakePlugin::path('Localized') . 'tests' . DS . 'TestCase' . DS);

        return $suite;
    }
}
