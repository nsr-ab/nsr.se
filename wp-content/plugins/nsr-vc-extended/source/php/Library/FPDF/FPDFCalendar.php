<?php

namespace VcExtended\Library\FPDF;

class FPDFCalendar extends FPDF
{
    protected $date;
    protected $squareHeight;
    protected $squareWidth;
    protected $tinySquareSize;

    protected $months = array("Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December");
    protected $longestMonth = "September";
    protected $weekdays = array("Måndag", "Tisdag", "Onsdag", "Torsdag", "Fredag", "Lördag", "Söndag");

    function __construct($orientation="L", $format="Letter")
    {
        parent::__construct($orientation, "mm", $format);

        // compute font size
        $this->tinySquareSize = 4;
        $this->headerFontSize = 70;
        $this->SetFont("Times", "B", $this->headerFontSize);
        $width = $this->w - $this->lMargin - $this->rMargin;
        while ($this->GetStringWidth($this->longestMonth) > $width - $this->tinySquareSize * 22) {
            --$this->headerFontSize;
            $this->SetFont("Times", "B", $this->headerFontSize);
        }
    }

    // useful date manipulation routines
    function JDtoYMD($date, &$year, &$month, &$day) {
        $string = JDToGregorian($date);
        $month = strtok($string, " -/");
        $day = strtok(" -/");
        $year = strtok(" -/");
    }

    function MDYtoJD($month, $day, $year) {
        if (! $month || ! $day || ! $year)
            return 0;
        $a = floor((14-$month)/12);
        $y = floor($year+4800-$a);
        $m = floor($month+12*$a-3);
        $jd = $day+floor((153*$m+2)/5)+$y*365;
        $jd += floor($y/4)-floor($y/100)+floor($y/400)-32045;
        return $jd;
    }

    function lastMonth($date) {
        $this->JDtoYMD($date, $year, $month, $day);
        if (--$month == 0) {
            $month = 12;
            $year--;
        }
        return GregorianToJD($month, $day, $year);
    }

    function nextMonth($date) {
        $this->JDtoYMD($date, $year, $month, $day);
        if (++$month > 12) {
            $month = 1;
            ++$year;
        }
        return GregorianToJD($month, $day, $year);
    }

    /*
    function isHoliday($date) {
        $this->JDtoYMD($date, $year, $month, $day);
        if ($month == 7 && $day == 4)
            return "Independence Day";
        if ($month == 1 && $day == 1)
            return "New Year's Day";
        if ($month == 12 && $day == 25)
            return "Christmas";
        if ($month == 11)
            {
            $dow = gmdate("w", jdtounix($date));
            if ($day == 11 && $dow > 0 && $dow < 6) // does the eleventh fall on a weekday?
                return "Veteran's Day";
            if ($dow == 1 && ($day == 12 || $day == 13))
                return "Veteran's Day";
            }
        if ($this->isWeekHoliday($date, 4, 4, 11)) // thursday of the fourth week of November
            return "Thanksgiving";
        if ($this->isWeekHoliday($date, 1, 3, 1))
            return "MLK, Jr. Day";
        if ($this->isWeekHoliday($date, 1, 3, 2))
            return "President's Day";
        if ($this->isWeekHoliday($date, 2, 1, 11))
            return "Election Day";
        if ($this->isWeekHoliday($date, 1, 1, 9))
            return "Labor Day";
        if ($this->isWeekHoliday($date, 1, 2, 10))
            return "Columbus Day";
        if ($this->isWeekHoliday($date, 1, 99, 5))
            return "Memorial Day";
        if ($this->isWeekHoliday($date, 0, 2, 5))
            return "Mother's Day";
        if ($this->isWeekHoliday($date, 0, 3, 6))
            return "Father's Day";
        return "";
    }
    */

    /*
    function isWeekHoliday($date, $dayOfWeek, $weekOfMonth, $monthOfDate) {
        $this->JDtoYMD($date, $year, $month, $day);
        if ($monthOfDate != $month)
            return 0;
        $jd = jdtounix($date);
        $dow = gmdate("w", $jd);
        if ($dow != $dayOfWeek)
        return 0;
        $daysInMonth = gmdate("t", $jd);
        if ($weekOfMonth > 5 && $day + 6 > $daysInMonth)
            return 1;
        if ($day > ($weekOfMonth - 1) * 7 && $day <= ($weekOfMonth * 7))
            return 1;
        return 0;
    }
    */

    function tinyCalendar($date, $square) {
        $this->JDtoYMD($date, $year, $month, $day);

        // print numbers in boxes
        //$wd=gmdate("w",jdtounix($date));
        $wd = (int)gmdate("N",jdtounix($date)) - 1;
        $cur = $date - $wd;
        $this->SetFont("Helvetica", "B", 10);
        $monthStr = $this->months[gmdate("n", jdtounix($date))-1]." $year";
        $this->JDtoYMD($date, $year, $month, $day);

        // save local copy of coordinates for future reference
        $x = $this->x;
        $y = $this->y;
        $this->Cell(7*$square, $square, $monthStr, 0, 0, "C");
        $y += $square;
        $this->SetXY($x, $y);
        $this->SetFontSize(8);
        for ($i = 1; $i <= 7; ++$i) {
            $day = strtoupper(gmdate("l", jdtounix($this->MDYtoJD(12,$i,2008))));
            $this->Cell($square, $square, $day[0], 0,0,"C");
        }
        $y += $square;
        $this->SetXY($x, $y);
        for ($k=0 ; $k<6 ; $k++) {
            for ($i=0 ; $i<7 ; $i++) {
                $this->JDtoYMD($cur++, $curYear, $curMonth, $curDay);
                if ($curMonth != $month)
                    $curDay = " ";
                $this->Cell($square, $square, $curDay, 0, 0, "R");
            }
            $y += $square;
            $this->SetXY($x, $y);
        }
    }

    function printDay($date) {
        $this->JDtoYMD($date,$year,$month,$day);
        if ($month == 1 && $day == 10){
            $this->SetXY($this->x, $this->y + $this->squareHeight / 2);
            $this->SetFont("Arial", "B", 10);
            $this->Cell($this->squareWidth,5,"Happy Birthday!", 0,0, "C");
        }
    }

    /*
    function printHoliday($date) {
        $x = $this->x;
        $y = $this->y;
        $height = 5.5;
        if ($this->squareHeight < 50)
            $height = 4;
        $widthPercent = .92;
        $fontSize = 11;
        $holiday = $this->isHoliday($date);
        if (strlen($holiday)) {
            $wd = gmdate("w",jdtounix($date));
            if ($wd != 0 && $wd != 6)
                $this->Cell($this->squareWidth, $this->squareHeight, "", 0, 0, "", true);
            $this->SetXY($x + $this->squareWidth * (1-$widthPercent)/2,$y + $this->squareHeight * 0.83);
            $this->SetFont("Helvetica", "B", $fontSize);
            $this->Cell($this->squareWidth * $widthPercent,$height,$holiday, 0, 0, "C");
        }
    }
    */

    function printMonth($date, $bydate=array()) {
        $this->date = $date;
        $this->JDtoYMD($date, $year, $month, $day);
        $this->AddPage();

        // compute size base on current settings
        $width = $this->w - $this->lMargin - $this->rMargin;
        $height = $this->h - $this->tMargin - $this->bMargin;

        // print prev and next calendars
        $this->setXY($this->lMargin,$this->tMargin);
        $this->tinyCalendar($this->lastMonth($date), $this->tinySquareSize);
        $this->setXY($this->lMargin+$width - $this->tinySquareSize * 7,$this->tMargin);
        $this->tinyCalendar($this->nextMonth($date), $this->tinySquareSize);

        // print header
        $firstLine = $this->tinySquareSize * 8 + $this->tMargin;
        $monthStr = strtoupper($this->months[gmdate("n", jdtounix($date))-1]) . " ";
        $monthStr .= strtoupper(gmdate ("Y", jdtounix($date)));
        $this->SetXY($this->lMargin,$this->tMargin);
        $this->SetFont("Times", "B", $this->headerFontSize);
        $this->Cell($width, $firstLine, $monthStr, 0,0, "C");

        // compute number of weeks in month.
        //$wd=gmdate("w",jdtounix($date));
        $wd = (int)gmdate("N",jdtounix($date)) - 1;
        $start = $date - $wd;
        $numDays = $this->nextMonth($date) - $start;
        $numWeeks = 0;
        while ($numDays > 0) {
            $numDays -= 7;
            ++$numWeeks;
        }

        // compute horizontal lines
        $this->squareHeight = ($height - 6 - $firstLine) / $numWeeks;
        $horizontalLines = array($firstLine,6);
        for ($i = 0; $i < $numWeeks; ++$i) {
            $horizontalLines[$i + 2] = $this->squareHeight;
        }

        // compute vertical lines
        $this->squareWidth = $width / 7;
        $verticalLines = array($this->lMargin, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth);
        
        // print days of week
        $x = 0;
        $this->SetFont("Times", "B", 12);
        for ($i = 1; $i <= 7; ++$i) {
            $x += $verticalLines[$i-1];
            $this->SetXY($x,$firstLine);
            //$day = gmdate("l", jdtounix($this->MDYtoJD(2,$i,2009)));
            $wd = utf8_decode($this->weekdays[ gmdate("N", jdtounix($this->MDYtoJD(12,$i,2008))) - 1]);
            $this->Cell($verticalLines[$i], 6, $wd, 0, 0, "C");
        }
        
        // print numbers in boxes
        //$wd = gmdate("w",jdtounix($date));
        $wd = (int)gmdate("N",jdtounix($date)) - 1;
        $cur = $date - $wd;
        $this->SetFont("Times", "B", 20);
        $x = 0;
        $y = $horizontalLines[0];
        for ($k=0 ; $k<$numWeeks ; $k++) {
            $y += $horizontalLines[$k+1];
            for ($i=0 ; $i<7 ; $i++) {
                $this->JDtoYMD($cur, $curYear, $curMonth, $curDay);
                $x += $verticalLines[$i];
                $this->squareWidth = $verticalLines[$i+1];
                if ($curMonth == $month) {
                    $isodate = sprintf("%04d-%02d-%02d", $curYear, $curMonth, $curDay);
                    if (isset($bydate[$isodate])) {
                        $t = str_replace("\n", ", ", trim($bydate[$isodate]));
                        $this->setXY($x, $y);
                        $this->Cell($this->squareWidth, $this->squareHeight, "", 0, 0, "", true);
                        $this->SetXY($x, $y + $this->squareHeight / 2);
                        $this->SetFont("Arial", "B", 10);
                        $this->Cell($this->squareWidth,5,utf8_decode($t), 0,0, "C");           
                    }
                    $this->SetFont("Times", "B", 20);
                    $this->SetXY($x,$y+1);
                    $this->Cell(5, 5, $curDay);
                }
                ++$cur;
            }
            $x = 0;
        }
        
        // print horizontal lines
        $ly = 0;
        $ry = 0;
        foreach ($horizontalLines as $key => $value) {
            $ly += $value;
            $ry += $value;
            $this->Line($this->lMargin,$ly,$this->lMargin+$width,$ry);
        }

        // print vertical lines
        $lx = 0;
        $rx = 0;
        foreach ($verticalLines as $key => $value) {
            $lx += $value;
            $rx += $value;
            $this->Line($lx,$firstLine,$rx,$firstLine + 6 + $this->squareHeight * $numWeeks);
        }
    }

}
?>