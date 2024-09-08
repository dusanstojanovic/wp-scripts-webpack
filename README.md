# Opinionated WP theme scaffolding, based on [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts)

## Usage

1. Find/replace `themename` to your theme name
2. Add your theme files
   * Source files are in `src/`
   * Compiled files are in `dist/`
3. `npm install`
4. `npm run start` or `npm start` : starts a watcher that will compile your files on change
5. `npm run build` : builds your files
   * `npm run bundle` : makes a zip file of your theme

### Linting
-   `npm run lint:scss` : checks all SCSS files against [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
-   `npm run lint:js` : checks all JS files against [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).

## Composer commands

```sh
$ composer install
```
-   `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   `composer lint:php` : checks all PHP files for syntax errors.
-   `composer make-pot` : generates a .pot file in the `languages/` directory.
