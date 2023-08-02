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

        $this->assertEquals($year.'-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-03-31', $firstQuarter->endDate->toDateString());
    }

    public function test_second_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::second();

        $this->assertEquals($year.'-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_third_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::third();

        $this->assertEquals($year.'-07-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-09-30', $firstQuarter->endDate->toDateString());
    }

    public function test_fourth_calendar_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::fourth();

        $this->assertEquals($year.'-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_first_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->fiscal();

        $this->assertEquals($year.'-07-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-09-30', $firstQuarter->endDate->toDateString());
    }

    public function test_second_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::second()->fiscal();

        $this->assertEquals($year.'-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_third_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::third()->fiscal();

        $this->assertEquals($year + 1 .'-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year + 1 .'-03-31', $firstQuarter->endDate->toDateString());
    }

    public function test_fourth_fiscal_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::fourth()->fiscal();

        $this->assertEquals($year + 1 .'-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year + 1 .'-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_get_next_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->next();

        $this->assertEquals($year.'-04-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-06-30', $firstQuarter->endDate->toDateString());
    }

    public function test_get_previous_quarter(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->previous();

        $this->assertEquals($year - 1 .'-10-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year - 1 .'-12-31', $firstQuarter->endDate->toDateString());
    }

    public function test_get_quarter_as_period_object(): void
    {
        $year = CarbonImmutable::today()->year;

        $firstQuarter = Quarter::first()->asPeriod();

        $this->assertEquals($year.'-01-01', $firstQuarter->startDate->toDateString());
        $this->assertEquals($year.'-03-31', $firstQuarter->endDate->toDateString());
        $this->assertInstanceOf(Period::class, $firstQuarter);
    }
}
