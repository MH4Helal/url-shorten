# URL Shortener Service

This is a URL shortening service built with Laravel, storing URL mappings in a JSON file or in memory if the file does not exist.

## Prerequisites

* PHP >= 8.1
* Composer
* Docker (if using Laravel Sail)
* Node.js and npm (for Laravel Mix, if needed)

## Installation

1.  **Clone the repository:**

    ```bash
    git clone 
    cd url-shorten
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Copy the `.env.example` file to `.env` and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

    * Set your `APP_KEY` by running `php artisan key:generate`.
    * Configure your database credentials in the `.env` file. (Although this app uses a json file or memory, laravel still needs database configuration)

4.  **Generate an App Key:**

    ```bash
    php artisan key:generate
    ```

## Running with Laravel Sail (Recommended)

1.  **Install Laravel Sail (if not already installed):**

    ```bash
    composer require laravel/sail --dev
    php artisan sail:install
    ```

2.  **Start Sail:**

    ```bash
    ./vendor/bin/sail up -d
    ```

3.  **Access the application:**

    * Open your web browser and go to `http://localhost`.

4.  **Run Artisan commands with Sail:**

    ```bash
    ./vendor/bin/sail artisan <command>
    ```

    * Example: `./vendor/bin/sail artisan test`

5.  **Stop Sail:**

    ```bash
    ./vendor/bin/sail down
    ```

## Running with Artisan (Without Sail)

1.  **Start the development server:**

    ```bash
    php artisan serve
    ```

    * The application will be available at `http://127.0.0.1:8000`.

2.  **Run Artisan commands:**

    ```bash
    php artisan <command>
    ```

    * Example: `php artisan test`

## Endpoints

* **`/encode` (POST):**
    * Encodes a URL to a shortened URL.
    * Request body: `{"url": "https://www.example.com/long/url"}`
    * Response: `{"short_url": "http://localhost/short/GeAi9K"}`
    * Example using curl:
        ```bash
        curl -X POST -H "Content-Type: application/json" -d '{"url": "[https://www.example.com/your/long/url](https://www.example.com/your/long/url)"}' http://localhost/encode
        ```
* **`/decode/{shortCode}` (GET):**
    * Decodes a shortened URL to its original URL.
    * Example: `/decode/GeAi9K`
    * Response: `{"original_url": "https://www.example.com/long/url"}`
* **`/short/{shortCode}` (GET):**
    * Redirects the short URL to its original URL.
    * Example: `/short/GeAi9K`
    * Response: Redirect to `https://www.example.com/long/url`
* **`/map` (GET):**
    * Returns the contents of the urls.json file or the in memory array. Used for debugging.

## Running Tests

### With Sail:

    
    ./vendor/bin/sail artisan test
    

## Running with Artisan (Without Sail)

    1.  **Start the development server:**

       
        php artisan serve
        

        * The application will be available at `http://127.0.0.1:8000`.

    2.  **Run Artisan commands:**

        
        php artisan <command>
    

        * Example: `php artisan test`

## Storage

* URL mappings are stored in `storage/app/urls.json`, or in memory if the file does not exist.
* Ensure that the web server has write permissions to the `storage/app` directory.

## Troubleshooting

* **Permissions:** If you encounter issues with file storage, check the permissions of the `storage/app` directory.
* **Logs:** Check the `storage/logs/laravel.log` file for error messages.
* **Clear Caches:** If you make changes to routes or configurations, clear the caches:
    * `./vendor/bin/sail artisan route:clear` or `php artisan route:clear`
    * `./vendor/bin/sail artisan config:clear` or `php artisan config:clear`
* **JSON File:** If the `urls.json` file is not being written to, check the permissions of the `storage/app` folder.
* **Database:** Even though this application uses a JSON file or memory, a database connection is still needed for Laravel sessions. Ensure that the database connection is configured correctly in the `.env` file.
* **In-Memory Storage:** If the web server is restarted, the in-memory storage will be cleared.
