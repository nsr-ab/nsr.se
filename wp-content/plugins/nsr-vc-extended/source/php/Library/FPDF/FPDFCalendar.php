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

    protected $monthIndex = 0;

    function __construct($orientation="L", $format="Letter")
    {
        parent::__construct($orientation, "mm", $format);

        // compute font size
        $this->tinySquareSize = 4;
        $this->headerFontSize = 30;
        $this->SetFont("Helvetica", "B", $this->headerFontSize);
        $width = $this->w - $this->lMargin - $this->rMargin;
        while ($this->GetStringWidth($this->longestMonth) > $width - $this->tinySquareSize * 22) {
            --$this->headerFontSize;
            $this->SetFont("Helvetica", "B", $this->headerFontSize);
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
            //$day = strtoupper(gmdate("l", jdtounix($this->MDYtoJD(12,$i,2008))));
            $day = strtoupper(substr(utf8_decode($this->weekdays[ gmdate("N", jdtounix($this->MDYtoJD(12,$i,2008))) - 1]), 0, 1));
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

    function printMonth($date, $bydate=array()) {
        $this->date = $date;
        $this->JDtoYMD($date, $year, $month, $day);

        $this->monthIndex++;

        // compute size base on current settings
        $width = ($this->w - $this->lMargin - $this->rMargin) * 0.48;
        $height = ($this->h - $this->tMargin - $this->bMargin) * 0.45;
        $ofsx = ($this->monthIndex % 4 == 1 || $this->monthIndex % 4 == 3) ? 0 : (($this->w - $this->lMargin - $this->rMargin) * 0.52);
        $ofsy = ($this->monthIndex % 4 == 1 || $this->monthIndex % 4 == 2) ? 0 : (($this->h - $this->tMargin - $this->bMargin) * 0.45);

        if ($this->monthIndex % 4 == 1) {
            $this->AddPage();

            // print general text
            $this->SetXY($this->lMargin, $this->tMargin + 2.02*$height);
            $this->SetFont("Helvetica", "B", 10);
            $this->Cell(($this->w - $this->lMargin - $this->rMargin), 5, utf8_decode("Blah blah blah generell text hamnar här. Kommer från NSR."), 0, 0, "C");

            $this->SetXY($this->lMargin, $this->tMargin + 2.02*$height + 5);
            $this->SetFont("Helvetica", "B", 10);
            $this->Cell(($this->w - $this->lMargin - $this->rMargin) , 5, utf8_decode("Blah blah blah generell text hamnar här. Kommer från NSR. Andra raden."), 0, 0, "C");    
        }

        // print prev and next calendars
/*
        $this->setXY($this->lMargin,$this->tMargin);
        $this->tinyCalendar($this->lastMonth($date), $this->tinySquareSize);
        $this->setXY($this->lMargin+$width - $this->tinySquareSize * 7,$this->tMargin);
        $this->tinyCalendar($this->nextMonth($date), $this->tinySquareSize);
*/

        // print header
        $firstLine = $this->tinySquareSize * 4 + $this->tMargin;
        $monthStr = strtoupper($this->months[gmdate("n", jdtounix($date))-1]) . " ";
        $monthStr .= strtoupper(gmdate ("Y", jdtounix($date)));
        $this->SetXY($this->lMargin + $ofsx, $this->tMargin + $ofsy);
        $this->SetFont("Helvetica", "B", $this->headerFontSize);
        $this->Cell($width, $firstLine, $monthStr, 0, 0, "C");

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
        $this->SetFont("Helvetica", "B", 12);
        for ($i = 1; $i <= 7; ++$i) {
            $x += $verticalLines[$i-1];
            $this->SetXY($x+$ofsx, $firstLine+$ofsy);
            //$day = gmdate("l", jdtounix($this->MDYtoJD(2,$i,2009)));
            $wd = utf8_decode($this->weekdays[ gmdate("N", jdtounix($this->MDYtoJD(12,$i,2008))) - 1]);
            $this->Cell($verticalLines[$i], 6, $wd, 0, 0, "C");
        }

        // print numbers in boxes
        //$wd = gmdate("w",jdtounix($date));
        $wd = (int)gmdate("N",jdtounix($date)) - 1;
        $cur = $date - $wd;
        $this->SetFont("Helvetica", "B", 10);
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
                        $this->setXY($x+$ofsx, $y+$ofsy);
                        $this->Cell($this->squareWidth, $this->squareHeight, "", 0, 0, "", true);
                        $this->SetXY($x+$ofsx, $y + $ofsy + $this->squareHeight/2);
                        $this->SetFont("Arial", "B", 10);
                        $this->Cell($this->squareWidth,5,utf8_decode($t), 0,0, "C");           
                    }
                    $this->SetFont("Helvetica", "B", 8);
                    $this->SetXY($x + $ofsx, $y + $ofsy - 0.5);
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
            $this->Line($this->lMargin+$ofsx, $ly+$ofsy, $this->lMargin+$width+$ofsx, $ry+$ofsy);
        }

        // print vertical lines
        $lx = 0;
        $rx = 0;
        foreach ($verticalLines as $key => $value) {
            $lx += $value;
            $rx += $value;
            $this->Line($lx+$ofsx,$firstLine+$ofsy,$rx+$ofsx,$firstLine + 6 + $this->squareHeight * $numWeeks + $ofsy);
        }
    }

}
?>