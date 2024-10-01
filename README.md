# DecodL API Download Demo

This project demonstrates how to integrate and use the [DecodL](https://decodl.net/) API for downloading various types of digital assets, including images and videos from popular stock providers. DecodL simplifies the process of acquiring licensed content for your projects by providing a unified API for multiple stock asset platforms.

![Peek 2024-10-01 15-07](https://github.com/user-attachments/assets/643a81b4-b9fc-4d26-8260-c51fcad040b1)

## Introduction

The DecodL API Download Demo is a simple web application that showcases how to interact with the DecodL API. It allows users to:

- Select a content provider (e.g., Shutterstock, Adobe Stock, Freepik)
- Enter an asset code or link
- Initiate a download request
- Monitor the download progress
- Retrieve the final download link

This demo serves as a starting point for developers looking to integrate DecodL's services into their own applications.

## Features

- User-friendly interface for initiating downloads
- Real-time progress updates
- Support for multiple stock content providers
- Error handling and display
- Responsive design using Tailwind CSS

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP 7.4 or higher
- Composer
- Web server (e.g., Apache, Nginx), or you can use the built-in php (`php -S localhost:8080`)
- Git (for cloning the repository)

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/decodl-api-demo.git
   cd decodl-api-demo
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Copy the `.env.sample` file to `.env`:
   ```
   cp .env.sample .env
   ```

4. Open the `.env` file and add your DecodL API credentials:
   ```
   APP_KEY=your-app-key
   AUTH_TOKEN=your-auth-token
   ```

   You can obtain these credentials by signing up at [DecodL](https://decodl.net/).

5. Configure your web server to serve the project directory.

## Usage

1. Open the application in your web browser.
2. Select a provider from the dropdown menu.
3. Enter the asset code or link in the input field.
4. Click the "Download" button to initiate the process.
5. Wait for the download to complete and click the provided link to access your asset.

## Configuration

The application uses environment variables for configuration. You can modify the following in your `.env` file:

- `APP_KEY`: Your DecodL API key
- `AUTH_TOKEN`: Your DecodL authentication token

For more advanced configuration options, you can modify the `api-proxy.php` file.

## Troubleshooting

If you encounter any issues:

1. Check that your `.env` file is correctly configured with valid API credentials.
2. Ensure that your web server has write permissions for the project directory.
3. Check the PHP error logs for any specific error messages.
4. Verify that you're using a supported PHP version (7.4+).

If problems persist, please open an issue on the GitHub repository.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

For more information about DecodL and its services, visit [https://decodl.net/](https://decodl.net/).
