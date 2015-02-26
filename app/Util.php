<?php
namespace TmlpStats;

use Carbon\Carbon;

class Util
{
    public static function toWords($str)
    {
        $output = preg_replace("/([A-Z])/", " $1", $str);
        return strtolower($output);
    }

    public static function camelCase($str)
    {
        $parts = explode('_', $str);
        $output = '';
        if (count($parts) > 0) {
            $output = array_shift($parts);
        }
        foreach($parts as $part) {

            $output .= ucfirst($part);
        }
        return $output ?: $str;
    }

    public static function objectToCamelCase($object)
    {
        $new = new \stdClass;
        $properties = get_object_vars($object);

        foreach($properties as $key => $value) {

            $newKey = static::camelCase($key);
            $new->$newKey = $value;
        }
        return $new;
    }

    public static function getExcelDate($excelDate)
    {
        $excelDateFormatted = \PHPExcel_Style_NumberFormat::toFormattedString($excelDate, "YYYY-MM-DD");
        if (!preg_match('/^\d\d\d\d-\d\d-\d\d$/', $excelDateFormatted)) {
            return false;
        }
        return Carbon::createFromFormat('Y-m-d', $excelDateFormatted)->startOfDay();
    }

    public static function parseUnknownDateFormat($dateStr)
    {
        $dateObj = null;
        if (preg_match("/^\d{5,}$/", $dateStr)) { // Unix timestamp
            $dateObj = Carbon::createFromFormat('U', $dateStr);
        } else if (preg_match("/^\d\d-\d\d-\d\d\d\d$/", $dateStr)) { // 01-01-2015
            $dateObj = Carbon::createFromFormat('Y-m-d', $dateStr);
        } else if (preg_match("/^\d\d\/\d\d\/\d\d\d\d$/", $dateStr)) { // 01/01/2015
            $dateObj = Carbon::createFromFormat('Y-m-d', $dateStr);
        } else if (preg_match("/^\d\d\d\d\/\d\d\/\d\d$/", $dateStr)) { // 2015/01/01
            $dateObj = Carbon::createFromFormat('Y-m-d', $dateStr);
        } else if (preg_match("/^\d\d\d\d-\d\d-\d\d$/", $dateStr)) { // 2015-01-01
            $dateObj = Carbon::createFromFormat('Y-m-d', $dateStr);
        } else if (preg_match("/^\d\d?-[a-zA-Z]{3}$/", $dateStr)) { // 1-Jan
            $dateObj = Carbon::createFromFormat('j-M', $dateStr);
        } else if (preg_match("/^\d\d?\/\d\d?$/", $dateStr)) { // 1/1
            $dateObj = Carbon::createFromFormat('n-j', $dateStr);
        } else if (preg_match("/^\d\d?-[a-zA-Z]{3}-\d\d$/", $dateStr)) { // 1-Jan-15
            $dateObj = Carbon::createFromFormat('j-M-y', $dateStr);
        }
        return $dateObj->startOfDay();
    }

    public static function getNameParts($name)
    {
        $parts = array(
            'firstName' => '',
            'lastName' => '',
        );

        $names = explode(' ', trim($name));
        if ($names && count($names) > 0) {
            $parts['firstName'] = $names[0];
            if (count($names) > 1) {
                $parts['lastName'] = trim(str_replace('.', '', $names[1]));
            }
        }
        return $parts;
    }
}