<?php

namespace App\HighloadBlock\Client;

class ClientEoTable extends \Bitrix\Main\Entity\DataManager
{
    // use \Opt\Main\Cache;
    // use \Opt\Main\Orm;

    public static function getTableName()
    {
        return 'px_client';
    }

    // public static function getObjectClass()
    // {
    //     return \Opt\HighloadBlock\Client\ClientEo::class;
    // }
    //
    // public static function getCollectionClass()
    // {
    //     return \Opt\HighloadBlock\Client\ClientEoCollection::class;
    // }

    public static function getMap()
    {
        $arMap = [
            'ID' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ],
            'NAME' => [
                'data_type' => 'string',
            ],

        ];
        return $arMap;
    }

    public static function getObjectClass()
    {
        return \App\HighloadBlock\Client\ClientEo::class;
    }

    public static function AddClientTask($count): void
    {
        static $isName;
        set_time_limit(0);
        for ($i = 0; $i < $count; $i++) {
            echo $i;

            if (!$id) {
                $lastIdClient = ClientEoTable::query()
                    ->setSelect(['ID'])
                    ->setOrder(['ID' => 'desc'])
                    ->setLimit(1)
                    ->exec()
                    ->fetch();
                $name = 'test_'. ++$lastIdClient['ID'];
            }
            else{
                $name = 'test_' .++$id;
            }

            $res = ClientEoTable::add(['NAME' => $name]);
            $id = $res->getId();

            for ($x = 0; $x < 500; $x++) {
                $status = 2;
                if ($x < 250) {
                    $status = 1;
                }
                $status = $arField = ['NAME' =>  substr(md5(time()), 0, 6), 'PRICE' => 1, 'CLIENT_ID' => $id, 'STATUS_ID' => $status];
                \App\HighloadBlock\Client\ClientTaskEoTable::add($arField);

            }

        }

    }

    public static function getIdAll(): array
    {
        return ClientEoTable::query()
            ->setSelect(['*'])
            ->exec()
            ->fetchAll();
    }
}
