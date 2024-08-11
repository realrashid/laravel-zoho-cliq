<?php

use RealRashid\Cliq\Cliq;
use Illuminate\Support\Facades\Http;

it('sends a message to specified recipients', function () {
    // Mock HTTP responses for access token and message sending
    Http::fake([
        'https://accounts.zoho.com/oauth/v2/token' => Http::response([
            'access_token' => 'mocked-access-token', // Mocked access token response
        ], 200),
        'https://cliq.zoho.com/api/v2/*' => Http::response(['success' => true], 200), // Mocked message sending response
    ]);

    // Initialize the Cliq instance and set the mocked access token
    $cliq = new Cliq();
    $cliq->setAccessToken('mocked-access-token');

    // Send a message to specified recipients
    $response = $cliq->to(['mazmeer@app.com', 'john@app.com', 'jane@app.com'])
        ->send("Hello team! This is a message from Laravel.");

    // Assert that the response indicates success
    expect($response[0]['success'])->toBeTrue();

    // Verify that the HTTP request was made with the correct URL and payload
    Http::assertSent(function ($request) {
        return $request->url() === 'https://cliq.zoho.com/api/v2/buddies/mazmeer@app.com/message'
            && $request['text'] === "Hello team! This is a message from Laravel.";
    });
});

it('sends a message to multiple buddies', function () {
    // Mock HTTP responses for access token and individual message sending
    Http::fake([
        'https://accounts.zoho.com/oauth/v2/token' => Http::response([
            'access_token' => 'mocked-access-token', // Mocked access token response
        ], 200),
        'https://cliq.zoho.com/api/v2/buddies/mazmeer@app.com/message' => Http::response(['success' => true], 200),
        'https://cliq.zoho.com/api/v2/buddies/john@app.com/message' => Http::response(['success' => true], 200),
        'https://cliq.zoho.com/api/v2/buddies/jane@app.com/message' => Http::response(['success' => true], 200),
    ]);

    // Initialize the Cliq instance and set the mocked access token
    $cliq = new Cliq();
    $cliq->setAccessToken('mocked-access-token');

    // Send a message to multiple buddies
    $response = $cliq->to(['mazmeer@app.com', 'john@app.com', 'jane@app.com'])
        ->send("Hello team! This is a message from Laravel.");

    // Assert that the response indicates success for each buddy
    expect($response[0]['success'])->toBeTrue();
    expect($response[1]['success'])->toBeTrue();
    expect($response[2]['success'])->toBeTrue();

    // Verify that HTTP requests were made with the correct URLs and payload
    Http::assertSent(function ($request) {
        $url = $request->url();
        $payload = $request->data();

        // Expected URLs for sending messages
        $expectedUrls = [
            'https://cliq.zoho.com/api/v2/buddies/mazmeer@app.com/message',
            'https://cliq.zoho.com/api/v2/buddies/john@app.com/message',
            'https://cliq.zoho.com/api/v2/buddies/jane@app.com/message',
        ];

        // Ensure the URL and payload match the expected values
        return in_array($url, $expectedUrls)
            && $payload['text'] === "Hello team! This is a message from Laravel.";
    });
});

it('sends a card message to the specified channel', function () {
    // Mock HTTP responses for access token and card message sending
    Http::fake([
        'https://accounts.zoho.com/oauth/v2/token' => Http::response([
            'access_token' => 'mocked-access-token', // Mocked access token response
        ], 200),
        'https://cliq.zoho.com/api/v2/*' => Http::response(['success' => true], 200), // Mocked card message sending response
    ]);

    // Initialize the Cliq instance and set the mocked access token
    $cliq = new Cliq();
    $cliq->setAccessToken('mocked-access-token');

    // Send a card message to the specified channel
    $response = $cliq->toChannel()->to('myalert')
        ->card(
            'System Alert: Critical Update',
            'https://example.com/images/system-alert-icon.png',
            'https://example.com/images/system-alert-thumbnail.png',
            'modern-inline',
            'Alert Bot',
            [
                [
                    'label' => 'View Details',
                    'hint' => 'Click to view more details',
                    'action_type' => 'open.url',
                    'web_url' => 'https://example.com/alert/details?alert_id=123',
                ],
                [
                    'label' => 'Dismiss',
                    'hint' => 'Click to dismiss the alert',
                    'action_type' => 'open.url',
                    'web_url' => 'https://example.com/alert/dismiss?alert_id=123',
                ]
            ]
        )
        ->send("A critical update has been issued: ");

    // Assert that the response indicates success
    expect($response[0]['success'])->toBeTrue();

    // Verify that the HTTP request was made with the correct URL and payload
    Http::assertSent(function ($request) {
        $payload = $request->data();

        // Check that the card data is included in the payload
        return $request->url() === 'https://cliq.zoho.com/api/v2/channelsbyname/myalert/message'
            && $payload['text'] === "A critical update has been issued: "
            && isset($payload['bot'])
            && $payload['bot']['name'] === 'Alert Bot'
            && isset($payload['card'])
            && $payload['card']['title'] === 'System Alert: Critical Update'
            && $payload['card']['icon'] === 'https://example.com/images/system-alert-icon.png'
            && $payload['card']['thumbnail'] === 'https://example.com/images/system-alert-thumbnail.png'
            && $payload['card']['theme'] === 'modern-inline'
            && count($payload['buttons']) === 2
            && $payload['buttons'][0]['label'] === 'View Details'
            && $payload['buttons'][0]['action']['data']['web'] === 'https://example.com/alert/details?alert_id=123'
            && $payload['buttons'][1]['label'] === 'Dismiss'
            && $payload['buttons'][1]['action']['data']['web'] === 'https://example.com/alert/dismiss?alert_id=123';
    });
});

it('sends a message to the specified channel', function () {
    // Mock HTTP responses for access token and message sending
    Http::fake([
        'https://accounts.zoho.com/oauth/v2/token' => Http::response([
            'access_token' => 'mocked-access-token', // Mocked access token response
        ], 200),
        'https://cliq.zoho.com/api/v2/*' => Http::response(['success' => true], 200), // Mocked message sending response
    ]);

    // Initialize the Cliq instance and set the mocked access token
    $cliq = new Cliq();
    $cliq->setAccessToken('mocked-access-token');

    // Send a message to the specified channel
    $response = $cliq->toChannel()->to('myalert')
        ->send("Hello team! This is a message from Laravel.");

    // Assert that the response indicates success
    expect($response[0]['success'])->toBeTrue();

    // Verify that the HTTP request was made with the correct URL and payload
    Http::assertSent(function ($request) {
        return $request->url() === 'https://cliq.zoho.com/api/v2/channelsbyname/myalert/message'
            && $request['text'] === "Hello team! This is a message from Laravel.";
    });
});
