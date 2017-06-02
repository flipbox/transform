# Transform
[![Latest Version](https://img.shields.io/github/release/flipbox/transform.svg?style=flat-square)](https://github.com/flipbox/transform/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/flipbox/transform/master.svg?style=flat-square)](https://travis-ci.org/flipbox/transform)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/flipbox/transform.svg?style=flat-square)](https://scrutinizer-ci.com/g/flipbox/transform/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/flipbox/transform.svg?style=flat-square)](https://scrutinizer-ci.com/g/flipbox/transform)
[![Total Downloads](https://img.shields.io/packagist/dt/flipboxdigital/transform.svg?style=flat-square)](https://packagist.org/packages/league/transform)

This package provides simple way to transform data.

## Installation

To install, use composer:

```
composer require flipboxdigital/transform
```

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Usage

```php
$raw = [
    'firstName' => 'foo',
    'lastName' => 'bar',
    'dateCreated' => new \DateTime(),
    'dateUpdated' => new \DateTime()
];

$data = Flipbox\Transform\Factory::item()
    ->transform(
        function($data) {

            return [
                'name' => [
                    'first' => $data['firstName'],
                    'last' => $data['firstName']
                ],
                'date' => [
                    'created' => $data['dateCreated']->format('c'),
                    'updated' => $data['dateUpdated']->format('c')
                ]
            ];

        },
        $raw
    );
```

## Contributing

Please see [CONTRIBUTING](https://github.com/flipbox/transform/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Flipbox Digital](https://github.com/flipbox)

## License

The MIT License (MIT). Please see [License File](https://github.com/flipbox/transform/blob/master/LICENSE) for more information.
