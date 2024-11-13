<?php

namespace Gtmassey\Quarter\Tests;

use Carbon\CarbonImmutable;
use Gtmassey\Period\Period;
use Gtmassey\Quarter\Quarter;

class QuarterTest extends TestCase
{
    public function test_construct(): void
    {
        $startDate = CarbonImmutable::parse('2022-10-01');
        $endDate = CarbonImmutable::parse('2022-10-31');

        $quarter = new Quarter($startDate, $endDate);

        $this->assertEquals($startDate->toDateString(), $quarter->startDate->toDateString());
        $this->assertEquals($endDate->toDateString(), $quarter->endDate->toDateString());
    }

    public function test_current_period(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2023-10-31'));

        $period = Quarter::current();

        $this->assertEquals('2023-10-01', $period->startDate->toDateString());
        $this->assertEquals('2023-12-31', $period->endDate->toDateString());
    }

    public function test_first_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first();

        $this->assertEquals($year . '-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-03-31', $firstQuarter->endDate->toDateString());
    }

    public function test_second_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::second();

        $this->assertEquals($year . '-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_third_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::third();

        $this->assertEquals($year . '-07-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-09-30', $firstQuarter->endDate->toDateString());
    }

    public function test_fourth_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::fourth();

        $this->assertEquals($year . '-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_first_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->toFiscal();

        $this->assertEquals($year . '-07-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-09-30', $firstQuarter->endDate->toDateString());
    }

    public function test_second_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::second()->toFiscal();

        $this->assertEquals($year . '-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_third_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::third()->toFiscal();

        $this->assertEquals($year + 1 . '-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year + 1 . '-03-31', $firstQuarter->endDate->toDateString());
    }

    public function test_fourth_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::fourth()->toFiscal();

        $this->assertEquals($year + 1 . '-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year + 1 . '-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_get_next_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->next();

        $this->assertEquals($year . '-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_get_previous_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->previous();

        $this->assertEquals($year - 1 . '-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year - 1 . '-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_get_quarter_as_period_object(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->asPeriod();

        $this->assertEquals($year . '-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year . '-03-31', $firstQuarter->endDate->toDateString());
        $this->assertInstanceOf(Period::class, $firstQuarter);
    }

    public function test_get_quarter_names(): void
    {
        $calendarYear = CarbonImmutable::today()->year;
        $fiscalYear = $calendarYear + 1;

        // Calendar quarters
        $firstCalendarQuarter = Quarter::first();
        $secondCalendarQuarter = Quarter::second();
        $thirdCalendarQuarter = Quarter::third();
        $fourthCalendarQuarter = Quarter::fourth();

        // Fiscal quarters
        $firstFiscalQuarter = Quarter::first()->toFiscal();
        $secondFiscalQuarter = Quarter::second()->toFiscal();
        $thirdFiscalQuarter = Quarter::third()->toFiscal();
        $fourthFiscalQuarter = Quarter::fourth()->toFiscal();

        // Assertions for calendar year quarters
        $this->assertEquals('Q1 CY' . substr($calendarYear, -2), $firstCalendarQuarter->getName());
        $this->assertEquals('Q2 CY' . substr($calendarYear, -2), $secondCalendarQuarter->getName());
        $this->assertEquals('Q3 CY' . substr($calendarYear, -2), $thirdCalendarQuarter->getName());
        $this->assertEquals('Q4 CY' . substr($calendarYear, -2), $fourthCalendarQuarter->getName());

        // Assertions for fiscal year quarters
        $this->assertEquals('Q1 FY' . substr($fiscalYear, -2), $firstFiscalQuarter->getName());
        $this->assertEquals('Q2 FY' . substr($fiscalYear, -2), $secondFiscalQuarter->getName());
        $this->assertEquals('Q3 FY' . substr($fiscalYear, -2), $thirdFiscalQuarter->getName());
        $this->assertEquals('Q4 FY' . substr($fiscalYear, -2), $fourthFiscalQuarter->getName());
    }

    public function test_current_quarter_names(): void
    {
        $calendarYear = CarbonImmutable::today()->year;
        $fiscalYear = $calendarYear + 1;

        // Calendar and fiscal quarters based on the current date
        $currentCalendarQuarter = Quarter::current();
        $currentFiscalQuarter = Quarter::current()->asFiscal();

        // Determine expected quarter names based on the current date
        $expectedCalendarQuarter = match (CarbonImmutable::today()->quarter) {
            1 => 'Q1 CY' . substr($calendarYear, -2),
            2 => 'Q2 CY' . substr($calendarYear, -2),
            3 => 'Q3 CY' . substr($calendarYear, -2),
            4 => 'Q4 CY' . substr($calendarYear, -2),
        };

        $expectedFiscalQuarter = match (CarbonImmutable::today()->quarter) {
            1 => 'Q3 FY' . substr($fiscalYear, -2),
            2 => 'Q4 FY' . substr($fiscalYear, -2),
            3 => 'Q1 FY' . substr($fiscalYear, -2),
            4 => 'Q2 FY' . substr($fiscalYear, -2),
        };

        // Assertions for current calendar and fiscal quarters
        $this->assertEquals($expectedCalendarQuarter, $currentCalendarQuarter->getName());
        $this->assertEquals($expectedFiscalQuarter, $currentFiscalQuarter->getName());
    }

}
