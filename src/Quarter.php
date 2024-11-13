<?php

namespace Gtmassey\Quarter;

use Carbon\CarbonImmutable;
use Gtmassey\Period\Period;

class Quarter extends Period
{
    public CarbonImmutable $startDate;

    public CarbonImmutable $endDate;

    public bool $isFiscal;

    public string $name;

    /**
     * Constructor with an optional $isFiscal parameter.
     */
    public function __construct(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate,
        ?string         $name = null,
        bool            $isFiscal = false,
    )
    {
        parent::__construct($startDate, $endDate);
        $this->isFiscal = $isFiscal;

        if (!isset($name)) {
            $month = $startDate->month;

            if ($this->isFiscal) {
                $this->name = match ($month) {
                    1, 2, 3 => 'Q3',
                    4, 5, 6 => 'Q4',
                    7, 8, 9 => 'Q1',
                    10, 11, 12 => 'Q2',
                    default => 'N/A',
                };
            } else {
                $this->name = match ($month) {
                    1, 2, 3 => 'Q1',
                    4, 5, 6 => 'Q2',
                    7, 8, 9 => 'Q3',
                    10, 11, 12 => 'Q4',
                    default => 'N/A',
                };
            }
        } else {
            $this->name = $name;
        }
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

    public static function first(bool $isFiscal = false): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear(),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
            name: "Q1",
        );
    }

    public static function second(bool $isFiscal = false): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(3),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
            name: "Q2",
        );
    }

    public static function third(bool $isFiscal = false): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(6),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
            name: "Q3",
        );
    }

    public static function fourth(bool $isFiscal = false): self
    {
        return new Quarter(
            startDate: $startOfYear = CarbonImmutable::today()->startOfYear()->addMonths(9),
            endDate: $startOfYear->addMonths(2)->endOfMonth(),
            name: "Q4",
        );
    }

    public static function currentCal(): self
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

    /**
     * @param int $year (format YYYY)
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
        $nextName = 'Q' . (($this->name === 'Q4') ? 1 : (int)substr($this->name, 1) + 1);
        return new Quarter(
            startDate: $this->endDate->addDays()->setTime(0, 0, 0),
            endDate: $this->endDate->addDay()->addMonths(2)->endOfMonth(),
            name: $nextName,
            isFiscal: $this->isFiscal,
        );
    }

    public function previous(): self
    {
        $prevName = 'Q' . (($this->name === 'Q1') ? 4 : (int)substr($this->name, 1) - 1);
        return new Quarter(
            startDate: $this->startDate->subDay()->subMonths(2)->startOfMonth(),
            endDate: $this->startDate->subDay()->setTime(23, 59, 59, 999999),
            name: $prevName,
            isFiscal: $this->isFiscal,
        );
    }

    public function toFiscal(): self
    {
        return new Quarter(
            startDate: $this->startDate->addMonths(6),
            endDate: $this->startDate->addMonths(6)->addMonths(2)->endOfMonth(),
            isFiscal: true,
        );
    }

    /**
     * Converts the current instance of Quarter from calendar to fiscal
     *
     * @return $this
     */
    public function asFiscal(): self
    {
        $this->isFiscal = true;
        $this->name = 'Q' . ((intval(substr($this->name, 1)) + 2 - 1) % 4 + 1);

        return $this;
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

    /**
     * Returns the name of the quarter in a format like "Q1 CY24" or "Q1 FY25".
     *
     * @return string
     */
    public function getName(): string
    {
        $year = $this->startDate->year;

        if ($this->isFiscal) {
            // Fiscal year logic: if the start date is on or after July 1, use the next calendar year
            $fiscalYear = $this->startDate->month >= 7 ? $year + 1 : $year;
            return "{$this->name} FY" . substr($fiscalYear, -2);
        } else {
            // Calendar year logic
            return "{$this->name} CY" . substr($year, -2);
        }
    }

}
