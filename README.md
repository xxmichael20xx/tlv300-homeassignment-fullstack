# TLV300 - Home Assignment Full Stack

## Setup Guide

### Prerequisites

Ensure you have the following installed:

- **Composer**: v2.6
- **Node.js**: v20.10
- **npm**: v10.2

### Steps to Set Up the Project

1. **Environment Configuration**

    - Locate the `.env.example` file in the project directory.
    - Create a new file in the same directory named `.env`.
    - Copy the contents of `.env.example` into the `.env` file.

2. **Database Setup**

    - Create a new database for the application. *(Note: A database isn't used for this trial task, just to prevent the missing database.)*

3. **API Key Configuration**

    - Open the `.env` file.
    - Find the line that says `WHOIS_API_KEY=`.
    - Replace the value with the provided API key from the email/message I sent.

4. **Install Dependencies**

    - Run `composer install` to install PHP dependencies.
    - Run `npm install` to install JavaScript dependencies.

5. **Running the Project**

   To run the project, you have two options:

    - **Option 1:**
        - Use the `php artisan serve` command to start the application.
        - Visit `http://localhost:8000` in your web browser.

    - **Option 2 (if using Laragon):**
        - Visit `http://tlv300-domain-info.test` or `https://tlv300-domain-info.test`, depending on your Laragon configuration.
