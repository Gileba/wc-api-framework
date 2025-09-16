# WC API Framework

A flexible WordPress plugin framework for integrating external APIs with WooCommerce products.

## Overview

The WC API Framework provides a robust foundation for building API integrations with WooCommerce. It handles common functionality like caching, product filtering, admin settings, and API communication, allowing developers to focus on API-specific logic.

## Features

- **Modular Architecture**: Base framework with extension system
- **Flexible Caching**: Configurable cache durations for different data types
- **Product Filtering**: Advanced filtering by brand, manufacturer, category, and tags
- **Admin Interface**: Comprehensive settings management with tabbed interface
- **Translation Ready**: Full internationalization support
- **Error Handling**: Robust error handling and logging
- **Performance Optimized**: Static caching and optimized database queries

## Architecture

### Base Framework (`wc-api-framework`)
- Core API client functionality
- Caching management
- Admin settings framework
- Product filtering system
- Helper utilities

### Extensions (`wc-api-{provider}`)
- Provider-specific API implementations
- Custom data mapping
- Provider-specific settings
- Custom filtering logic

## Installation

1. Install the base framework plugin
2. Install the desired API extension plugin
3. Configure API credentials in the admin panel
4. Set up product filtering rules
5. Configure cache settings

## Development

### Creating a New API Extension

1. Create a new plugin that extends the base framework
2. Implement the required interface methods
3. Add provider-specific settings
4. Handle API response mapping
5. Test with your WooCommerce store

### Required Interface Methods

- `get_api_endpoint()`
- `get_credentials_fields()`
- `parse_api_response()`
- `map_product_data()`
- `validate_credentials()`

## Requirements

- WordPress 5.0+
- PHP 7.4+
- WooCommerce 3.0+

## License

GPL2

## Changelog

*This is the first release of the WC API Framework.*
