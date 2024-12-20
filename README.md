# Opinionated WP theme scaffolding, based on [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts)

## Usage

```sh
$ npm install
```

1. Find/replace `themeslug` to your theme name slug
2. Add your theme files
   * Source files are in `src/`
   * Compiled files are in `build/`
3. `npm install`
4. `npm run start` or `npm start` : watch for changes and compile
5. `npm run build`: compile and minimize all files

## Linting
-   `npm run lint:scss` : checks all `.scss` files against [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
-   `npm run lint:js` : checks all `.js` files against [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
- `npm run packages-update`: update packages
- `npm run check-engines`: check engines
- `npm run check-licenses`: check licenses

## Composer commands

```sh
$ composer install
```
-   `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   `composer lint:php` : checks all PHP files for syntax errors.
-   `composer make-pot` : generates a .pot file in the `languages/` directory.

### TODO

- Find a way for blocks' js to be compiled in their directories
