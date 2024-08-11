<?php

namespace RealRashid\Cliq;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Exception;

class Cliq
{
    /**
     * The access token for authenticating API requests with Zoho Cliq.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The default recipient type for messages. Options include 'buddies', 'channelsbyname', etc.
     *
     * @var string
     */
    protected $sendTo = 'buddies';

    /**
     * A list of channels or recipients to which messages will be sent.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * An array containing API endpoint URLs for Zoho Cliq interactions.
     *
     * @var array
     */
    protected $endpoints = [];

    /**
     * The message content to be sent to Zoho Cliq.
     *
     * @var string
     */
    protected $message;

    /**
     * The card data structure for sending interactive messages to Zoho Cliq.
     *
     * @var array
     */
    protected $card;

    /**
     * Constructor to set default values if provided.
     *
     * Initializes the Cliq instance with optional attributes for channel and send_to settings.
     * This allows users to configure default channels and recipient types for message sending.
     *
     * @param array $attributes Associative array with optional 'channel' and 'send_to' keys.
     *                          - 'channel': (string) The default channel to send messages to.
     *                          - 'send_to': (string) The default recipient type (e.g., 'buddies', 'channelsbyname').
     */
    public function __construct(array $attributes = [])
    {
        // Set default channel if provided in the attributes
        if (isset($attributes['channel'])) {
            $this->setDefaultChannel($attributes['channel']);
        }

        // Set default recipient type if provided in the attributes
        if (isset($attributes['send_to'])) {
            $this->sendTo = $attributes['send_to'];
        }
    }

    /**
     * Sets the access token for authenticating API requests.
     *
     * @param string $accessToken The access token to be set.
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Gets the access token, fetching a new one if not already set.
     *
     * @return string The access token.
     * @throws Exception If there's an error obtaining the access token.
     */
    public function getAccessToken(): string
    {
        if (!$this->accessToken) {
            $this->setAccessToken($this->fetchAccessToken());
        }
        return $this->accessToken;
    }

    /**
     * Fetches a new access token from Zoho Cliq.
     *
     * @return string The fetched access token.
     * @throws Exception If there's an error obtaining the access token.
     */
    protected function fetchAccessToken(): string
    {
        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'client_id' => config('cliq.client_id'),
            'client_secret' => config('cliq.client_secret'),
            'grant_type' => 'client_credentials',
            'scope' => 'ZohoCliq.Webhooks.CREATE',
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            throw new Exception('Error obtaining access token: ' . $data['error']);
        }

        return $data['access_token'];
    }

    /**
     * Sets the default channel or user ID to send the message to.
     *
     * Adds a channel to the list of default communication channels
     * for sending messages, simplifying repeated operations.
     *
     * @param string|array $channels The channel or user ID.
     */
    public function setDefaultChannel($channels)
    {
        $this->channels = is_array($channels) ? $channels : [$channels];
    }

    /**
     * Sets the target type to 'channelsbyname'.
     *
     * @return self
     */
    public function toChannel(): self
    {
        $this->sendTo = 'channelsbyname';
        return $this;
    }

    /**
     * Sets the target type to 'buddies'.
     *
     * @return self
     */
    public function toBuddy(): self
    {
        $this->sendTo = 'buddies';
        return $this;
    }

    /**
     * Sets the specific channel or user ID to send the message to.
     *
     * @param string|array $channels The channel or user ID.
     * @return self
     */
    public function to($channels): self
    {
        $this->setDefaultChannel($channels);
        return $this;
    }

    /**
     * Generates the API endpoint URL for sending the message.
     *
     * Constructs endpoint URLs based on the channels and the recipient type
     * to enable sending messages to the specified targets.
     */
    protected function generateEndpoints()
    {
        // Get the base URL from the config and trim any trailing slashes
        $baseUrl = rtrim(config('cliq.api_base_url'), '/');

        foreach ($this->channels as $channel) {
            // Construct the full endpoint URL
            $this->endpoints[] = "{$baseUrl}/{$this->sendTo}/{$channel}/message";
        }
    }

    /**
     * Sends the message to the configured targets.
     *
     * Sends the prepared message to each endpoint generated for the
     * specified channels or recipients using the Zoho Cliq API.
     *
     * @param string $message The message to be sent.
     * @return array The responses from Zoho Cliq.
     * @throws RuntimeException If there's an error encoding the JSON payload.
     */
    public function send($message)
    {
        $this->message = $message;
        $this->generateEndpoints();
        $responses = [];

        foreach ($this->endpoints as $endpoint) {
            $payload = $this->createPayload();
            $response = Http::withToken($this->getAccessToken())->post($endpoint, $payload);
            $responses[] = $response->json();
        }

        return $responses;
    }

    /**
     * Creates the request payload for sending the message.
     *
     * Constructs the payload array containing the message text and card
     * details if applicable, to be sent to Zoho Cliq API.
     *
     * @return array The payload to be sent in the request body.
     */
    protected function createPayload(): array
    {
        $payload = ['text' => $this->message];
        if (is_array($this->card)) {
            $payload = array_merge($payload, $this->card);
        }
        return $payload;
    }

    /**
     * Sets card details for richer messages.
     *
     * Configures a card with additional interactive elements to enhance
     * messages sent through Zoho Cliq, such as buttons and images.
     *
     * @param string $title The title of the card.
     * @param string $icon URL of the icon for the card.
     * @param string $thumbnail URL of the thumbnail for the card.
     * @param string $theme Theme for the card, e.g., 'modern-inline'.
     * @param string $botName Name of the bot.
     * @param array $buttons Array of buttons for the card.
     *                       Each button should be an associative array with keys 'label', 'hint', 'action_type', and 'web_url'.
     * @return self
     */
    public function card(string $title, string $icon, string $thumbnail, string $theme = 'modern-inline', string $botName, array $buttons): self
    {
        $formattedButtons = [];

        foreach ($buttons as $button) {
            $formattedButtons[] = [
                'label' => $button['label'],
                'hint' => $button['hint'],
                'action' => [
                    'type' => $button['action_type'],
                    'data' => [
                        'web' => $button['web_url']
                    ],
                ],
            ];
        }

        $this->card = [
            'bot' => [
                'name' => $botName,
            ],
            'card' => [
                'title' => $title,
                'icon' => $icon,
                'thumbnail' => $thumbnail,
                'theme' => $theme,
            ],
            'buttons' => $formattedButtons,
        ];
        return $this;
    }
}
