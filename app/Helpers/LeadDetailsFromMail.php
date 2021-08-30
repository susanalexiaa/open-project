<?php

namespace App\Helpers;

use Spatie\Regex\Regex;


class LeadDetailsFromMail
{

    public static function getDetailsFromBody($body): array
    {
        $name = Regex::match('/Ф\.?И\.?О\.?:? ?-? ?(.*)\r\n/i', $body)->groupOr(1, 'ФИО не определено');
        $email = Regex::match('/E-Mail:? ?-? ?(.*)\r\n/i', $body)->groupOr(1, '');
        $phone = Regex::match('/Телефон:? ?-? ?(.*)\r\n/i', $body)->groupOr(1, '');
        $source = Regex::match('/Источник:? ?-? ?(.*)\r\n/i', $body)->groupOr(1, 'Не определено');
        $price = Regex::match('/(([Сс]тоимость)|([Сс]умма)) ?заказа ?(-|:) ?((\d+ ?)+) (руб\.?|₽)\r\n/i', $body)->groupOr(5,0);

        $price = intval( str_replace(' ', '', $price));
        //zadarma
        if( $phone == '' ){
            $phone = Regex::match('/(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}/', $body)->resultOr('');
        }

        return compact('name', 'email', 'phone', 'source', 'price');
    }
}
