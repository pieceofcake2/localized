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
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('I18n', 'I18n');

/**
 * LocaleFilesTest class
 *
 * Checks that the translations shipped in Locale/ are found by I18n.
 */
class LocaleFilesTest extends CakeTestCase
{
    /**
     * Use the plugin's Locale directory as the application's locale path
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        App::build(['locales' => [CakePlugin::path('Localized') . 'Locale' . DS]], App::RESET);
        Configure::write('Cache.disable', true);
        I18n::clear();
    }

    /**
     * Clear the loaded translations
     *
     * @return void
     */
    public function tearDown(): void
    {
        I18n::clear();

        parent::tearDown();
    }

    /**
     * Every locale only has the LC_MESSAGES and LC_TIME categories CakePHP reads
     *
     * @return void
     */
    public function testLocaleDirectories(): void
    {
        $path = CakePlugin::path('Localized') . 'Locale' . DS;
        $locales = glob($path . '*', GLOB_ONLYDIR);
        $this->assertNotEmpty($locales);

        foreach ($locales as $locale) {
            foreach (glob($locale . DS . '*', GLOB_ONLYDIR) as $category) {
                $this->assertContains(basename($category), ['LC_MESSAGES', 'LC_TIME'], $category);
            }
        }
    }

    /**
     * The Hungarian translations of the cake domain are loaded
     *
     * @return void
     */
    public function testHungarianTranslations(): void
    {
        Configure::write('Config.language', 'hun');

        $this->assertSame('Hiba', __d('cake', 'Error'));
        $this->assertSame('Belső hiba történt!', __d('cake', 'An Internal Error Has Occurred.'));
    }

    /**
     * Translations of another locale are loaded, as a control for the test above
     *
     * @return void
     */
    public function testGermanTranslations(): void
    {
        Configure::write('Config.language', 'deu');

        $this->assertSame('%s ungültig', __d('cake', 'Invalid %s'));
    }
}
