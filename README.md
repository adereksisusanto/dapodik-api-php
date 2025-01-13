# DAPODIK API for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adereksisusanto/dapodik-api-php.svg?style=flat-square)](https://packagist.org/packages/adereksisusanto/dapodik-api-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/adereksisusanto/dapodik-api-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/adereksisusanto/dapodik-api-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/adereksisusanto/dapodik-api-php.svg?style=flat-square)](https://packagist.org/packages/adereksisusanto/dapodik-api-php)

## Informasi
Dalam penggunaan API Dapodik berarti Anda secara sadar memberikan data individu setiap entitas Dapodik kepada pihak ketiga. Segala bentuk penyalahgunaan dapat diancam dengan hukuman pidana sesuai dengan UU Perlindungan Data Pribadi No 27 Tahun 2022 Pasal 67. Mohon anda benar-benar telah paham dan yakin akan hal tersebut.

## Requirement
Pastikan [Dapodik](https://dapo.kemdikbud.go.id/unduhan) sudah terinstal di komputer Anda atau di VPS.

## Installation

You can install the package via composer:

```bash
composer require adereksisusanto/dapodik-api-php
```

## Usage

```php
// default host & port
$dapodik = new Adereksisusanto\DapodikAPI();

// custom host & port
$dapodik = new Adereksisusanto\DapodikAPI('custom_host','custom_port');

$auth = $dapodik->login('username', 'password', 'kode_registrasi');
$sekolah = $auth->sekolah();
var_dump($sekolah);

$api = $dapodik->api('token', 'npsn');
$sekolah = $api->sekolah();
var_dump($sekolah);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [adereksisusanto](https://github.com/adereksisusanto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
