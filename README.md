# Opinionated WP theme scaffolding, based on [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts)

## Usage

1. Find/replace `themename` to your theme name
2. Add your theme files
   * Source files are in `src/`
   * Compiled files are in `dist/`
3. `npm install`
4. for development: `npm start`
5. for production: `npm run build`
6. for archiving pure theme to zip: `npm run bundle`

### Composer commands

```sh
$ composer install
```
-   `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   `composer lint:php` : checks all PHP files for syntax errors.
-   `composer make-pot` : generates a .pot file in the `languages/` directory.
