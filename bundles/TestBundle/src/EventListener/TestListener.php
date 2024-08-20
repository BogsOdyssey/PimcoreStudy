<?php

namespace TestBundle\EventListener;

use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Event\Model\DocumentEvent;
use Pimcore\Model\Element\ValidationException;
use Symfony\Component\VarDumper\VarDumper;
use App\Model\HasTextFieldInterface;


class TestListener
{
    /**
     * @throws ValidationException
     */
    public function onPreUpdate(ElementEventInterface $e): void
    {
        $object = $e->getObject();

//        var_dump("Test");
//        VarDumper::dump('It`s working');

        if ($object instanceof HasTextFieldInterface) {

            $text = $object->getTextField();

            if (!str_contains($text, 'Вкусно и точка')) {
                throw new ValidationException('Текст должен содержать "Вкусно и точка"');
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function onPreAdd(ElementEventInterface $e): void
    {
        $object = $e->getObject();

        if ($object instanceof HasTextFieldInterface) {
            $object->setNumberField1(random_int(1, 100));
            $object->setNumberField2(random_int(100, 200));
        }
    }
}
