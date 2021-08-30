<?php 

namespace App\Helpers;
use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneHelper
{
    public static function getStandartisedNumber($phoneNumber)
    {
        $phoneNumber = trim($phoneNumber);

        try {
            $phoneNumber = PhoneNumber::make($phoneNumber, 'RU')->formatInternational();
        } catch (\libphonenumber\NumberParseException $e) {
            $phoneNumber = $phoneNumber;
        }

        $phoneNumber = str_replace('-', ' ', $phoneNumber);
        $phoneNumber = str_replace('+', '', $phoneNumber); 
        $phoneNumber = str_replace(' ', '', $phoneNumber);

        return $phoneNumber;
    }
}