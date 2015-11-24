<?php

namespace sourcetoad\bbii2\components;

class DateTimeCalculation {
    static public function shortDate($timestamp) {
        $df = \Yii::$app->dateFormatter;
        return $df->formatDateTime(self::userTimestamp($timestamp), 'short', '');
    }
    
    static public function longDate($timestamp) {
        $df = \Yii::$app->dateFormatter;
        return $df->formatDateTime(self::userTimestamp($timestamp), 'long', '');
    }
    
    static public function medium($timestamp) {
        $df = \Yii::$app->dateFormatter;
        return $df->formatDateTime(self::userTimestamp($timestamp), 'medium', 'short');
    }
    
    static public function long($timestamp) {
        $df = \Yii::$app->dateFormatter;
        return $df->formatDateTime(self::userTimestamp($timestamp), 'long', 'short');
    }
    
    static public function full($timestamp) {
        $df = \Yii::$app->dateFormatter;
        return $df->formatDateTime(self::userTimestamp($timestamp), 'long', 'medium') . ' ' . self::userTimezoneNotation();
    }
    
    /**
     * Precursor function to convert timestamp for user
     * @param string $timestamp timestamp format 'yyyy-MM-dd hh:mm:ss'
     * @return string timestamp format 'yyyy-MM-dd hh:mm:ss'
     */
    static public function userTimestamp($timestamp) {
        if (\Yii::$app->user->isGuest) {
            return $timestamp;
        }
        $timezone = BbiiMember::find(\Yii::$app->user->identity->id )->timezone;
        if (empty($timezone)) {
            return $timestamp;
        } else {
            return self::convertTimestamp($timestamp, $timezone);
        }
    }
    
    /**
     * Convert timestamp from server time to time in target timezone
     * @param string $timestamp timestamp format 'yyyy-MM-dd hh:mm:ss'
     * @param string $timezone e.g. 'Europe/Paris'
     * @return string timestamp format 'yyyy-MM-dd hh:mm:ss'
     */
    static public function convertTimestamp($timestamp, $timezone) {
        $serverTimeZone = date_default_timezone_get();
        $time = CDateTimeParser::parse($timestamp,'yyyy-MM-dd hh:mm:ss');
        $dateTimeZone1 = new DateTimeZone($serverTimeZone);
        $dateTimeZone2 = new DateTimeZone($timezone);
        $dateTime1 = new DateTime("now", $dateTimeZone1);
        $dateTime2 = new DateTime("now", $dateTimeZone2);
        $diff = ($dateTimeZone2->getOffset($dateTime1) - $dateTimeZone1->getOffset($dateTime1));
        $time + =  $diff;
        return date('Y-m-d H:i:s', $time);
    }
    
    /**
     * Return the timezone notation for the user
     * @return string
     */
    static public function userTimezoneNotation() {
        if (\Yii::$app->user->isGuest) {
            $timezone = date_default_timezone_get();
        } else {
            $timezone = BbiiMember::find(\Yii::$app->user->identity->id )->timezone;
            if (empty($timezone)) {
                $timezone = date_default_timezone_get();
            }
        }
        $dateTime = new DateTime(); 
        $dateTime->setTimeZone(new DateTimeZone($timezone)); 
        return $dateTime->format('T'); 
    }
}
?>