Sure! Here's the revised release post with the first-person perspective:

---

## 🎉 Announcing the Release of Laravel Zoho Cliq Package! 🚀

I’m excited to announce the release of the **Laravel Zoho Cliq** package, a powerful tool designed to bring seamless integration between Laravel applications and Zoho Cliq. Whether you're building a new app or enhancing an existing one, this package makes it incredibly easy to send messages, notifications, and rich content directly from your Laravel application to Zoho Cliq.

### Key Features:

- **Send Messages to Multiple Users**: Effortlessly broadcast messages to multiple Zoho Cliq users from your Laravel application.
- **Single User Messaging**: Send personalized messages to individual users with ease.
- **Rich Card Messaging**: Create visually engaging messages with interactive cards.
- **Channel Messaging**: Communicate with specific channels to keep your teams informed and engaged.

### How to Get Started

1. **Install the Package**: Add the Laravel Zoho Cliq package to your Laravel application using Composer:
   
   ```bash
   composer require realrashid/laravel-zoho-cliq
   ```

2. **Publish Configuration**: Publish the configuration file to set up your credentials:
   
   ```bash
   php artisan cliq:install
   ```

3. **Configure OAuth Credentials**: Obtain your **Client ID** and **Client Secret** from Zoho Cliq’s API Console:
   
   - Visit [Zoho API Console](https://api-console.zoho.com/).
   - Click the **Get Started** button and select **Self Client**.
   - Click **Create**, then **OK** when prompted.
   - Copy the **Client ID** and **Client Secret**, and add them to your `.env` file:
     
     ```dotenv
     CLIQ_CLIENT_ID=your-client-id
     CLIQ_CLIENT_SECRET=your-client-secret
     CLIQ_DEFAULT_CHANNEL=your-default-channel
     CLIQ_DEFAULT_SEND_TO=buddies
     ```

### Example Usages

- **Send a Message to Multiple Users**:
  ```php
  Route::get('/send-buddy-message', function () {
      $response = Cliq::to(['user1@example.com', 'user2@example.com'])
          ->send("Hello team! This is a message from Laravel.");

      return response()->json($response);
  });
  ```

- **Send a Message with a Card**:
  ```php
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
          ->send("I’m excited to announce the release of our new feature!");

      return response()->json($response);
  });
  ```

- **Send a Message to a Channel**:
  ```php
  Route::get('/send-message-channel', function () {
      $response = Cliq::toChannel()->to('general')
          ->send("Good morning, everyone! Here’s the latest update from Laravel.");

      return response()->json($response);
  });
  ```

### Why Use Laravel Zoho Cliq?

- **Seamless Integration**: Easily integrate Zoho Cliq into your Laravel applications with minimal configuration.
- **Rich Features**: Leverage advanced features like rich card messaging to enhance your communication.
- **Open Source**: Contribute and benefit from a package that’s actively maintained and community-driven.

### Get Involved

I welcome contributions and feedback! If you have suggestions or improvements, please check out the [GitHub repository](https://github.com/realrashid/laravel-zoho-cliq) and consider contributing.

### Support

If you find this package useful and would like to support my work, you can buy me a coffee. Your support helps me continue to maintain and improve the package.

[![Buy me a coffee](https://cdn.buymeacoffee.com/buttons/default-orange.png)](https://www.buymeacoffee.com/realrashid)

Thank you for using Laravel Zoho Cliq!

---

Feel free to adjust any part of the post as needed!
