<?php
/**
 * Date: 18.02.16
 * Time: 13:36
 */

namespace AppBundle\Tests\Validator\Assert;


use AppBundle\Entity\Category;
use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Event\ProductEvents;
use AppBundle\ProductFilter\ProductFilter;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssertValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function setUp()
    {
        parent::setUp();
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    public function testEntities()
    {
        $product = new Product();
        $this->assertEquals('', (string)$this->validator->validate($product));
    }

    /**
     * @dataProvider getCuratorDecisions
     */
    public function testCuratorDecision($data)
    {
        $curatorDecision = new CuratorDecision();
        $curatorDecision->setStatus($data['invalid']);
        $this->assertEquals('Choose a valid status.', $this->validator->validate($curatorDecision)[0]->getMessage());
        $curatorDecision->setStatus($data['valid']);
        $this->assertEquals(0, $this->validator->validate($curatorDecision)->count());
    }

    public function getCuratorDecisions()
    {
        return ['first' => [
            ['valid' => CuratorDecision::STATUS_WAIT, 'invalid' => 'qwerty'],
            ['valid' => CuratorDecision::STATUS_APPROVE, 'invalid' => null],
            ['valid' => CuratorDecision::STATUS_REJECT, 'invalid' => 32132131],
        ]];
    }

    /**
     * @dataProvider getProductFilters
     */
    public function testProductFilter($data, $count, $messages)
    {

        $productFilter = new ProductFilter();
        $productFilter->setCategory($data['category'])->setStatus($data['status'])->setDirection($data['direction'])
            ->setFieldName($data['fieldName']);
        $this->assertEquals($count, $this->validator->validate($productFilter)->count());
        foreach ($messages as $message) {
            $this->assertContains($message, (string)$this->validator->validate($productFilter));
        }
    }

    public function getProductFilters()
    {
        //Нет возможности проверить Юнит тестированием права доступа к фильтру.
        return [
            [
                ['category' => new Category(), 'status' => 'invalid status', 'direction' => 'dsf', 'fieldName' => 'wer'],
                3,
                ['Выберите верный тип сортировки', 'Выберите верный фильтр', 'Выберите верное направление сортировки']
            ],
            [
                ['category' => new Category(), 'status' => 'inus', 'direction' => ProductFilter::DIRECTION_ASC, 'fieldName' => 'wer'],
                2,
                ['Выберите верный тип сортировки', 'Выберите верный фильтр']
            ],
            [
                ['category' => new Category(), 'status' => 'inus', 'direction' => ProductFilter::DIRECTION_ASC, 'fieldName' => ProductFilter::FIELD_ENABLED_AT],
                1,
                ['Выберите верный фильтр']
            ],
            [
                ['category' => new Category(), 'status' => null, 'direction' => 'j', 'fieldName' => ProductFilter::FIELD_ENABLED_AT],
                1,
                ['Выберите верное направление сортировки',]
            ],
        ];
    }
}