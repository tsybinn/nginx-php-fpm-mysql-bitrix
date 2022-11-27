<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 */

use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\UserFieldTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;

/**
 * Компонент для вывода статуса товаров (остатки)
 */
class TaskClientComponent extends \CBitrixComponent
{
    /**
     * @param $arParams
     *
     * @return array
     */
    // public function onPrepareComponentParams($arParams)
    // {
    //     // Нужно вернуть результат компонента, а не выводить
    //     $arParams['RETURN'] = $arParams['RETURN'] ?? false;
    //     return $arParams;
    // }

    /**
     * @inheritDoc
     */
    public function executeComponent()
    {
        // $arClient = App\HighloadBlock\Client\ClientEoTable::getAll();
        ini_set('memory_limit', '1024M');
        $start = microtime(true);


       //   App\HighloadBlock\Client\ClientEoTable::AddClientTask(100);

        // $rsClient = App\HighloadBlock\Client\ClientTaskEoTable::query()
        //     ->setSelect(['ID','NAME'])
        //   //  ->setLimit(100)
        //     ->exec();
        //  $arResult = $rsClient->fetchAll();



        $rsClient = App\HighloadBlock\Client\ClientTaskEoTable::query()

            ->registerRuntimeField(
                new Reference(
                    'CODE',
                    App\HighloadBlock\Client\ClientTaskStatusEoTable::class,
                    \Bitrix\Main\ORM\Query\Join::on('this.STATUS_ID', 'ref.ID')
                ))
            // cnt - название поля
            // ->registerRuntimeField("SUM", [
            //     // тип вычисляемого поля
            //     "data_type" => "integer",
            //     // агрегатная функция (count, max, sum, avg...) и поле для подстановки
            //     "expression" => 'SUM(IF(%1$s = "P", %2$s,0))',
            //     ['STATUS_CODE','PRICE'],
            // ])
            ->registerRuntimeField(
                new ExpressionField(
                    'SUM_P',
                    'SUM(IF(%1$s = "1", %2$s,0))',
                    ['STATUS_ID','PRICE']
                )
            )
            ->registerRuntimeField(
                new ExpressionField(
                    'SUM_F',
                    'SUM(IF(%1$s = "2", %2$s,0))',
                    ['STATUS_ID','PRICE']
                )
            )
            ->registerRuntimeField("CNT", [
                    // тип вычисляемого поля
                    "data_type" => "integer",
                    // агрегатная функция (count, max, sum, avg...) и поле для подстановки
                    "expression" => ["count(%s)", "ID"],
                ])
            ->registerRuntimeField(
                new Reference(
                    'CLIENT',
                    App\HighloadBlock\Client\ClientEoTable::class,
                    \Bitrix\Main\ORM\Query\Join::on('this.CLIENT_ID', 'ref.ID')
                ))

            ->setSelect(['CLIENT_ID', 'SUM_P', 'SUM_F','CNT', 'CLIENT_NAME'=>'CLIENT.NAME', 'STATUS_CODE'=>'CODE.CODE'])

        //    ->setLimit(100)
           ->exec();
$res = $rsClient->fetchAll();

echo 5;
          //   while($arClient = $rsClient->fetch()){
            //
            //   if()
            //
            //
            //     $arResult[] = [
            //         'ID' => $arClient['CLIENT_ID'],
            //         if($arResult){
            //             'SUM_DONE' => '',
            //         }
            //
            //         'SUM_PROCESS' => '',
            //         'TOTAL_TASK' => ''
            //     ];
            //
            // }




        echo 'Time Script: '.round(microtime(true) - $start, 4).sec;
        return $this->includeComponentTemplate();

    }
}
