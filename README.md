# Quarter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Tests][ico-tests]][link-tests]

Retrieve start and end dates for calendar or fiscal quarters for any year with ease! 

## Installation

You can install the package via composer:

```bash
composer require gtmassey/quarter
```

This package is an extension of the gtmassey/period package. You can find the documentation for that package [here](https://github.com/gtmassey/period). 

## Usage

The package provides a `Quarter` class which contains static methods for each quarter of a year, and additional methods that can be chained on that allow for mutations of the quarter to specific years, calendar types, and dates.

```php
//Jan 1, YYYY - Mar 31, YYYY
Quarter::first();

//Apr 1, YYYY - Jun 30, YYYY
Quarter::second();

//Jul 1, YYYY - Sep 30, YYYY
Quarter::third();

//Oct 1, YYYY - Dec 31, YYYY
Quarter::fourth();
```

As an extension of the `Period` class from [gtmassey/period](https://github.com/gtmassey/period), the resulting Quarter object is structured the same way:

```
Gtmassey\Quarter\Quarter {#6221
  +startDate: Carbon\CarbonImmutable @1672531200 {#6224
    date: 2023-01-01 00:00:00.0 UTC (+00:00),
  },
  +endDate: Carbon\CarbonImmutable @1680307199 {#6220
    date: 2023-03-31 23:59:59.999999 UTC (+00:00),
  },
}
```

### Specifying Years

If you want to specify the year of the quarter, you can chain the `year()` method on the quarter. Note that you have to format the year as `YYYY`. If you don't, it will take the year literally, i.e. the year 0020.

```php
//Jan 1, 2020 - Mar 31, 2020
Quarter::first()->year(2020);

//Jan 1, 0020 - Mar 31, 0020
Quarter::first()->year(20);

//Jan 1, 1995 - Mar 31, 1995
Quarter::first()->year(1995);
```

### Changing the Resulting Object

By default, the `Quarter` class returns a `Quarter` object. You can default to the parent `Period` object by calling the `asPeriod()` method:

```php
Quarter::first()->asPeriod();
```

which will result in an instance of Period like so:

```
Gtmassey\Period\Period {
  +startDate: Carbon\CarbonImmutable @1672531200 {
    date: 2023-01-01 00:00:00.0 UTC (+00:00),
  },
  +endDate: Carbon\CarbonImmutable @1680307199 {
    date: 2023-03-31 23:59:59.999999 UTC (+00:00),
  },
}

```

### Changing Calendar Types

So far, the quarter methods have been assuming a calendar year for the quarter dates. If you want to use the fiscal year in which the first quarter starts on July 1, you can chain the `fiscal()` method:

```php
//July 1, YYYY - September 30, YYYY
Quarter::first()->fiscal();
```

You can chain the `year` and `fiscal` methods together:

```php
//July 1, 1995 - September 30, 1995
Quarter::first()->year(1995)->fiscal();
```

and if you want the resulting object to be an instance of the parent `Period` class, you can chain the `asPeriod()` method:

```php
//July 1, 1995 - September 30, 1995
Quarter::first()->year(1995)->fiscal()->asPeriod();
```

### Start and End Dates Only

Sometimes you only need to access the start and end dates of a given quarter, without accessing the entire range. To do that, simply add the `startDate()` or `endDate()` method to the chain:

```php
//July 1, 2020
Quarter::third()->year(20)->startDate();
```

### Past and Future Quarters:

If you have a given quarter object, and you want to get the next quarter, you can use the `next()` method:

```php
$quarter = Quarter::first()->year(2020);
//Jan 1, 2020 - Mar 31, 2020
$next = $quarter->next();
//Apr 1, 2020 - Jun 30, 2020
```

To get the previous quarters from a given Quarter object, use the `previous()` method:

```php
$quarter = Quarter::first()->year(2020);
//Jan 1, 2020 - Mar 31, 2020
$previous = $quarter->previous();
//Oct 1, 2019 - Dec 31, 2019
```

Finally, you can access the current quarter by calling the `current()` method:

```php
$current = Quarter::current();
//return the dates for the current quarter, regardless of calendar or fiscal dates.
```

Note that if you try to use the `fiscal()` method on the `current()` method, it will not throw an exception, but it will return quarter dates six months ahead. This is because `Quarter::first()` assumes January 1st for the start date, and `Quarter::first()->fiscal()` adds six months to achieve the July 1 start date. 

This can lead to some unexpected results. If you wish to calculate the current fiscal quarter, you can simply use `current()` to get the current quarter's start and end dates, and then you can use the `next()` and `previous()` methods to traverse throughout the quarters. 

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

To contribute, fork the repo, create a new branch, and submit a pull request. I will do my best to review them in a timely manner. 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/gtmassey/quarter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/gtmassey/quarter.svg?style=flat-square
[ico-tests]: https://github.com/gtmassey/quarter/actions/workflows/run-tests.yml/badge.svg

[link-packagist]: https://packagist.org/packages/gtmassey/quarter
[link-downloads]: https://packagist.org/packages/gtmassey/quarter
[link-tests]: https://github.com/gtmassey/quarter/actions/workflows/run-tests.yml
