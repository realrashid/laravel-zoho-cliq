# Laravel Zoho Cliq

[![Latest Version on Packagist](https://img.shields.io/packagist/v/realrashid/laravel-zoho-cliq.svg?style=flat-square)](https://packagist.org/packages/realrashid/laravel-zoho-cliq)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/realrashid/laravel-zoho-cliq/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/realrashid/laravel-zoho-cliq/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/realrashid/laravel-zoho-cliq/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/realrashid/laravel-zoho-cliq/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/realrashid/laravel-zoho-cliq.svg?style=flat-square)](https://packagist.org/packages/realrashid/laravel-zoho-cliq)

## Introduction

Laravel Zoho Cliq is a Laravel package designed to seamlessly integrate with Zoho Cliq, allowing you to send messages and notifications directly from your Laravel application. Whether you need to communicate with individual users or broadcast messages to channels, this package provides an easy-to-use interface to interact with Zoho Cliq's API.

## Installation

To get started, install the package via Composer:

```bash
composer require realrashid/laravel-zoho-cliq
```

Then, publish the configuration file:

```bash
php artisan cliq:install
```

## Configuration

After installing the package, you need to configure the OAuth credentials and default settings. 

### Obtaining Client ID and Secret

1. Go to [Zoho API Console](https://api-console.zoho.com/).
2. If you do not have any client, click the **Get Started** button.
3. A modal will appear. Select **Self Client** and click **Create**.
4. You will be prompted with "Are you sure to enable self-client?" Click **OK**.
5. Copy the **Client ID** and **Client Secret** provided.
6. Add them to your `.env` file:

```dotenv
CLIQ_API_BASE_URL=https://cliq.zoho.com/api/v2
CLIQ_CLIENT_ID=your-client-id
CLIQ_CLIENT_SECRET=your-client-secret
CLIQ_DEFAULT_CHANNEL=your-default-channel
CLIQ_DEFAULT_SEND_TO=buddies
```

## Usage

Here are some examples of how to use the package to interact with Zoho Cliq:

### Send a Message to Multiple Users

Send a message to multiple Zoho Cliq users:

```php
// Route to send a message to multiple users
Route::get('/send-buddy-message', function () {
    $response = Cliq::to(['user1@example.com', 'user2@example.com'])
        ->send("Hello team! This is a message from Laravel.");

    return response()->json($response);
});
```

### Send a Message to a Single User

Send a message to a single Zoho Cliq user:

```php
// Route to send a message to a single user
Route::get('/send-single-buddy-message', function () {
    $response = Cliq::to('user@example.com')
        ->send("Hi there! This is a personal message from Laravel.");

    return response()->json($response);
});
```

### Send a Message with a Card

Send a rich message with a card to a Zoho Cliq channel:

```php
// Route to send a message with a card
Route::get('/send-card-message', function () {
    $response = Cliq::toChannel()->to('alerts')
        ->card(
            'New Feature Released!',
            'https://example.com/image.jpg',
            'https://example.com/image.jpg',
            'modern-inline',
            'Release Bot',
            [
                [
                    'label' => 'Learn More',
                    'hint' => 'Click to learn more about the feature',
                    'action_type' => 'open.url',
                    'web_url' => 'https://example.com/feature',
                ],
                [
                    'label' => 'Feedback',
                    'hint' => 'Provide feedback on the new feature',
                    'action_type' => 'open.url',
                    'web_url' => 'https://example.com/feedback',
                ]
            ]
        )
        ->send("We are excited to announce the release of our new feature!");

    return response()->json($response);
});
```

### Send a Message to a Channel

Send a message to a specific Zoho Cliq channel:

```php
// Route to send a message to a channel
Route::get('/send-message-channel', function () {
    $response = Cliq::toChannel()->to('general')
        ->send("Good morning, everyone! Here’s the latest update from Laravel.");

    return response()->json($response);
});
```

## Testing

Run the tests using Composer:

```bash
composer test
```

## Changelog

For recent changes, see the [CHANGELOG](CHANGELOG.md).

## Contributing

We welcome contributions! Please see [CONTRIBUTING](CONTRIBUTING.md) for more details.

## Security Vulnerabilities

If you discover a security vulnerability, please report it to us responsibly by emailing [realrashid@gmail.com](mailto:realrashid@gmail.com). We will address the issue as promptly as possible.

## Credits

- [Rashid Ali](https://github.com/realrashid)
- [All Contributors](../../contributors)

## Support My Work 

If you find this package useful and would like to support my work, you can buy me a coffee. Your support helps keep this project alive and thriving. Thank you!

[![Buy me a coffee](https://cdn.buymeacoffee.com/buttons/default-orange.png)](https://www.buymeacoffee.com/realrashid)

## License

This package is licensed under the MIT License. See [License File](LICENSE.md) for more information.

<br />
<p align="center"> <b>Made with ❤️ from Pakistan</b> </p>
