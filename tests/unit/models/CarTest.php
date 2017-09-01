<?php

namespace models;


use app\models\Car;
use app\fixtures\Car as CarFixture;

class CarTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'cars' => [
                'class' => CarFixture::className(),
                'dataFile' => codecept_data_dir() . 'cars.php'
            ]
        ]);
    }


    /**
     * Тест для добавление машины
     */
    public function testCreate()
    {
        $car = new Car();

        $car->name = 'Название';
        $car->model = 'bmw';
        expect_that($car->save());
    }

    /**
     * Форма должна выдать ошибку, если пытаюсь отправить пустую форму
     */
    public function testCreateEmptyFormSubmit()
    {
        $car = new Car();

        expect_not($car->validate());
        expect_not($car->save());
    }

    /**
     * Возможность удалить машину
     */
    public function testDelete()
    {
        $car = Car::findOne(1);

        expect_that($car !== null);

        expect_that($car->delete());
    }

    /**
     * Возможность изменить машину
     */
    public function testUpdate()
    {
        $car = Car::findOne(['name' => 'test1']);

        expect_that($car !== null);

        $car->name = 'mashina_ne_sushestvuet';

        expect_that($car->save());

        $updatedCar = Car::findOne(['name' => 'mashina_ne_sushestvuet']);

        expect_that($updatedCar !== null);
    }
}