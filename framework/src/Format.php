<?php

class Format
{
    /**
     *  Форматирование даты по указанному шаблону
     * @param $date - дата в формате yyyy-mm-dd
     * @param string $template - шаблон форматирования
     *      d - день месяца, 2 цифры с ведущим нулём        (от 01 до 31)
     *      j - день месяца без ведущего нуля               (от 1 до 31)
     *      F - полное наименование месяца                  (от января до декабря)
     *      m - порядковый номер месяца с ведущим нулём     (от 01 до 12)
     *      n - порядковый номер месяца без ведущего нуля   (от 1 до 12)
     *      Y - порядковый номер года, 4 цифры              (например, 2019)
     *      y - номер года, 2 цифры                         (например, 19)
     *  Доступные делители:
     *      Пробел, точка, запятая, двоеточие, точка с запятой, тире, нижнее подчёркивание.
     *
     * @return string
     */
    public static function date($date, $template = "d.m.Y")
    {
        $date = explode("-", $date);

        $day = $date[2];
        $month = $date[1];
        $year = $date[0];

        return self::getFormatDate($day, $month, $year, $template);
    }

    /**
     *  Получение форматированной даты
     * @param $day      - полученный день
     * @param $month    - полученный месяц
     * @param $year     - полученный год
     * @param $template - шаблон форматирования
     * @return string   - дата в указанном шаблоне
     */
    private static function getFormatDate($day, $month, $year, $template)
    {
        $count = 0;
        do {
            $tempArray = self::getTemplateArray($template, $count);
            $temp = $tempArray['explode'];
            $delimiter = $tempArray['delimiter'];
            $count++;
        } while (count($temp) == 1 && $count < 7);

        return self::getDateString($day, $month, $year, $temp, $delimiter);
    }

    /**
     *  Получение даты в указанном шаблоне
     * @param $day          - полученный день
     * @param $month        - полученный месяц
     * @param $year         - полученный год
     * @param $template     - шаблон форматирования
     * @param $delimiter    - разделитель из шаблона
     * @return string       - дата в казанном шаблоне
     */
    private static function getDateString($day, $month, $year, $template, $delimiter)
    {
        $count = 1;
        $date = "";
        foreach ($template as $item) {
            if ($item == mb_strtoupper($item)) {
                switch (mb_strtolower($item)) {
                    case "f":
                        $date .= self::getStringMonth($month) . self::checkCount($count, 3, $delimiter);
                        break;
                    case "y":
                        $date .= $year . self::checkCount($count, 3, $delimiter);
                        break;
                }
            }
            else {
                switch (mb_strtolower($item)) {
                    case "d":
                        $date .= $day . self::checkCount($count, 3, $delimiter);
                        break;
                    case "j":
                        $date .= self::getSmallDay($day) . self::checkCount($count, 3, $delimiter);
                        break;
                    case "m":
                        $date .= $month . self::checkCount($count, 3, $delimiter);
                        break;
                    case "n":
                        $date .= self::getSmallMonth($month) . self::checkCount($count, 3, $delimiter);
                        break;
                    case "y":
                        $date .= self::getSmallYear($year) . self::checkCount($count, 3, $delimiter);
                        break;
                }
            }
            $count++;
        }

        return $date;
    }

    /**
     *  Получение значения дня месяца без ведущего нуля
     * @param $day
     * @return int
     */
    private static function getSmallDay($day)
    {
        return (int) $day;
    }

    /**
     *  Получение порядкового номера месяца без ведущего нуля
     * @param $month
     * @return int
     */
    private static function getSmallMonth($month)
    {
        return (int) $month;
    }

    /**
     *  Получение полного наименования месяца
     * @param $month
     * @return string
     */
    private static function getStringMonth($month)
    {
        switch ($month) {
            case "01":
                return "января";
            case "02":
                return "февраля";
            case "03":
                return "марта";
            case "04":
                return "апреля";
            case "05":
                return "мая";
            case "06":
                return "июня";
            case "07":
                return "июля";
            case "08":
                return "августа";
            case "09":
                return "сентября";
            case "10":
                return "октября";
            case "11":
                return "ноября";
            case "12":
                return "декабря";
            default:
                return "месяца";
        }
    }

    /**
     *  Получение порядкового номера года, состоящего из 2 цифр
     * @param $year
     * @return int
     */
    private static function getSmallYear($year)
    {
        return $year % 100;
    }

    /**
     *  Получение массива из шаблона по возможным разделителям
     * @param $template
     * @param $count
     * @return array
     */
    private static function getTemplateArray($template, $count)
    {
        switch ($count) {
            case 0:
                return [
                    'explode' => explode(" ", $template),
                    'delimiter' => " "
                ];
            case 1:
                return [
                    'explode' => explode(".", $template),
                    'delimiter' => "."
                ];
            case 2:
                return [
                    'explode' => explode(",", $template),
                    'delimiter' => ","
                ];
            case 3:
                return [
                    'explode' => explode(":", $template),
                    'delimiter' => ":"
                ];
            case 4:
                return [
                    'explode' => explode(";", $template),
                    'delimiter' => ";"
                ];
            case 5:
                return [
                    'explode' => explode("-", $template),
                    'delimiter' => "-"
                ];
            case 6:
                return [
                    'explode' => explode("_", $template),
                    'delimiter' => "_"
                ];

        }
    }

    /**
     *  Проверка на последнее значение в дате или времени (чтобы не ставить разделитель)
     * @param $count
     * @param $countValue
     * @param $delimiter
     * @return string
     */
    private static function checkCount($count, $countValue, $delimiter)
    {
        if ($count < $countValue)
            return $delimiter;
        else
            return '';
    }
}