<?php

it('publishes the configuration file when running the install command', function () {
    // Ensure the configuration file does not exist before running the command
    // This avoids any residual files affecting the test
    if (file_exists(config_path('cliq.php'))) {
        unlink(config_path('cliq.php'));
    }

    // Execute the install command and simulate the user input as 'no' to skip GitHub prompt
    $this->artisan('cliq:install')
        ->expectsOutput('Installing the Zoho Cliq...') // Expect initial installation message
        ->expectsOutput('Initiating the publication of Cliq Config...') // Expect config publication message
        ->expectsOutput('Laravel Zoho Cliq installed successfully.') // Expect successful installation message
        ->expectsOutput('Thank you for using Zoho Cliq!') // Expect thank you message
        ->expectsQuestion('Wanna show Zoho Cliq some love by starring it on GitHub? (yes/no) [no]:', 'no') // Simulate 'no' input to skip GitHub prompt
        ->assertExitCode(0); // Ensure the command exits successfully with code 0

    // Assert that the configuration file was published as expected
    expect(file_exists(config_path('cliq.php')))->toBeTrue();

    // Clean up by deleting the configuration file
    unlink(config_path('cliq.php'));
});

it('displays an error message if Cliq is already installed', function () {
    // Create a dummy config file to simulate that the Cliq package is already installed
    file_put_contents(config_path('cliq.php'), '<?php return [];');

    // Run the install command and capture the output
    $this->artisan('cliq:install')
        ->assertExitCode(0) // Ensure the command exits with code 0
        ->expectsOutput('Cliq is already installed.'); // Expect the error message indicating that Cliq is already installed

    // Clean up by deleting the configuration file
    unlink(config_path('cliq.php'));
});

it('prompts user to show support and opens GitHub repo if yes', function () {
    // Ensure the configuration file does not exist before running the command
    // This avoids any residual files affecting the test
    if (file_exists(config_path('cliq.php'))) {
        unlink(config_path('cliq.php'));
    }

    // Execute the install command and simulate the user input as 'yes' to trigger GitHub prompt
    $this->artisan('cliq:install')
        ->expectsOutput('Installing the Zoho Cliq...') // Expect initial installation message
        ->expectsOutput('Initiating the publication of Cliq Config...') // Expect config publication message
        ->expectsOutput('Laravel Zoho Cliq installed successfully.') // Expect successful installation message
        ->expectsOutput('Thank you for using Zoho Cliq!') // Expect thank you message
        ->expectsQuestion('Wanna show Zoho Cliq some love by starring it on GitHub? (yes/no) [no]:', 'yes') // Simulate 'yes' input to open GitHub page
        ->expectsOutput('Opening GitHub repository page...') // Expect message indicating GitHub repo will be opened
        ->assertExitCode(0); // Ensure the command exits successfully with code 0

    // Clean up by deleting the configuration file
    unlink(config_path('cliq.php'));
});

it('does not open GitHub repo if user selects no', function () {
    // Ensure the configuration file does not exist before running the command
    // This avoids any residual files affecting the test
    if (file_exists(config_path('cliq.php'))) {
        unlink(config_path('cliq.php'));
    }

    // Execute the install command and simulate the user input as 'no' to skip GitHub prompt
    $this->artisan('cliq:install')
        ->expectsOutput('Installing the Zoho Cliq...') // Expect initial installation message
        ->expectsOutput('Initiating the publication of Cliq Config...') // Expect config publication message
        ->expectsOutput('Laravel Zoho Cliq installed successfully.') // Expect successful installation message
        ->expectsOutput('Thank you for using Zoho Cliq!') // Expect thank you message
        ->expectsQuestion('Wanna show Zoho Cliq some love by starring it on GitHub? (yes/no) [no]:', 'no') // Simulate 'no' input to skip GitHub prompt
        ->expectsOutput('No worries! Thank you for using Zoho Cliq.') // Expect message indicating no action will be taken
        ->assertExitCode(0); // Ensure the command exits successfully with code 0

    // Clean up by deleting the configuration file
    unlink(config_path('cliq.php'));
});
