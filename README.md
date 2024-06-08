# VOUCHERS API Documentation

## Overview

This API allows you to interact with our service. Below is the OpenAPI specification for the API.

## Installation
To install and run the Laravel application using Laravel Sail, follow these steps:

1. **Clone the repository**:
   ```sh
   git clone https://github.com/your-repo/vouchers-api.git
   cd vouchers-api
   ```

2. **Install dependencies**:
   ```sh
   composer install
   ```

3. **Set up environment variables**:
   Copy the `.env.example` file to `.env` and update the environment variables as needed.
   ```sh
   cp .env.example .env
   ```

4. **Build the Sail environment**:
   ```sh
   vendor/bin/sail build
   ```

5. **Start the Sail environment**:
   ```sh
   vendor/bin/sail up
   ```

6. **Run database migrations**:
   ```sh
   vendor/bin/sail artisan migrate
   ```

7. **Run tests**:
   ```sh
   vendor/bin/sail artisan test
   ```

Your Laravel application should now be up and running. You can access it at `http://localhost`.


## OpenAPI Specification

The OpenAPI specifications for the Vouchers API can be found in the `vouchers-openapi.yaml` file.
