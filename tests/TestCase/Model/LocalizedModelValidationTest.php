<?php
App::uses('CzValidation', 'Localized.Validation');
App::uses('MxValidation', 'Localized.Validation');

class LocalizedModelValidationTest extends CakeTestCase
{
    /**
     * LocalizedModelValidationTest::testLocalizedValidation()
     *
     * @return void
     */
    public function testLocalizedValidation(): void
    {
        $value = '1234';
        $result = Validation::postal($value, null, 'cz');
        $this->assertFalse($result);

        $value = '12345';
        $result = Validation::postal($value, null, 'cz');
        $this->assertTrue($result);
    }

    /**
     * LocalizedModelValidationTest::testLocalizedValidation()
     *
     * @return void
     */
    public function testLocalizedValidationOnValidate(): void
    {
        $this->Post = ClassRegistry::init('LocalizedPost');
        $data = [
            'postal' => '1234',
        ];
        $this->Post->create();
        $this->Post->set($data);
        $result = $this->Post->validates();
        $this->assertFalse($result);

        $data = [
            'postal' => '12345',
        ];
        $this->Post->create();
        $this->Post->set($data);
        $result = $this->Post->validates();
        $this->assertTrue($result);
    }
}

class LocalizedPost extends CakeTestModel
{
    public $useTable = false;

    public $validate = [
        'postal' => [
            'valid' => [
                'rule' => ['postal', null, 'mx'],
                'message' => 'Must be valid mexico postal code',
            ],
        ],
    ];
}
