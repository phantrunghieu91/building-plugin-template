# JinsPlugin

JinsPlugin is a WordPress plugin that serves as a learning project.

## Features So Far

- Custom Post Types: Easily manage and display custom content types.
- Widgets: Add dynamic content to your site's sidebars.
- Extensible API: Develop custom features and integrations.
- Using gulp to auto compile sass, typescript and minify outcome JS, CSS files.

## Installation

1. Download the plugin from the GitHub repository or via the WordPress plugin directory.
2. Upload the `jins-plugin` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

After installation, JinsPlugin adds new options to the WordPress admin panel, allowing you to manage custom post types, widgets, and other settings.

## Development

### Requirements

- PHP 7.4 or higher
- Composer for managing PHP dependencies
- Node.js and npm/pnpm for managing JavaScript and CSS assets

### Setting Up the Development Environment

1. Clone the repository to your local machine.
2. Navigate to the `jins-plugin` directory.
3. Run `npm install` or `pnpm install` to install JavaScript and CSS dependencies.
4. Use `gulp` to compile and watch SCSS and TypeScript files.

**Note: Only run step 3 and 4 if you planning continue work on this project.

## Contributing

Contributions are welcome! Please read our contributing guidelines for details on how to submit pull requests, report issues, and suggest improvements.

## License

JinsPlugin is open-sourced software licensed under the GPUv2 or later.