<?php

namespace Gtmassey\Quarter;

use Carbon\CarbonImmutable;
use Gtmassey\Period\Period;

class Quarter extends Period
{
    public CarbonImmutable $startDate;

    public CarbonImmutable $endDate;

    public static function first(): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear(),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
        );
    }

    public static function second(): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(3),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
        );
    }

    public static function third(): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(6),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
        );
    }

    public static function fourth(): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(9),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
        );
    }

    /**
     * @param  int  $year (format YYYY)
     * @return $this
     */
    public function year(int $year): self
    {
        //given the year, we need to mutate $this to be the same year
        $this->startDate = $this->startDate->setYear($year);
        $this->endDate = $this->endDate->setYear($year);

        return $this;
    }

    public function next(): self
    {
        return new Quarter(
            startDate: $this->endDate->addDays()->setTime(0, 0, 0),
            endDate: $this->endDate->addDay()->addMonths(2)->endOfMonth(),
        );
    }

    public function previous(): self
    {
        return new Quarter(
            startDate: $this->startDate->subDay()->subMonths(2)->startOfMonth(),
            endDate: $this->startDate->subDay()->setTime(23, 59, 59, 999999),
        );
    }

    public function fiscal(): self
    {
        return new Quarter(
            startDate: $this->startDate->addMonths(6),
            endDate: $this->startDate->addMonths(6)->addMonths(2)->endOfMonth()
        );
    }

    /**
     * @throws \Exception
     */
    public static function current(): self
    {
        $today = CarbonImmutable::today();
        //given today's date, determine the current calendar quarter
        //1st quarter = Jan, Feb, Mar
        //2nd quarter = Apr, May, Jun
        //3rd quarter = Jul, Aug, Sep
        //4th quarter = Oct, Nov, Dec
        return match ($today->month) {
            1, 2, 3 => self::first(),
            4, 5, 6 => self::second(),
            7, 8, 9 => self::third(),
            10, 11, 12 => self::fourth(),
            default => throw new \Exception('Invalid month'),
        };
    }

    public function startDate(): CarbonImmutable
    {
        return $this->startDate;
    }

    public function endDate(): CarbonImmutable
    {
        return $this->endDate;
    }

    public function asPeriod(): Period
    {
        return new Period(
            startDate: $this->startDate,
            endDate: $this->endDate,
        );
    }
}
