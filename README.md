# Simple webpack WP theme worflow based on @wordpress/scripts (https://www.npmjs.com/package/@wordpress/scripts)

Find/replace _themename_ to your theme name

1. `npm install`
2. for development: `npm start`
3. for production: `npm run build`
4. for archiving pure theme to zip: `npm run bundle`

### Composer stuff (i don't use)

```sh
$ composer install
```

-   `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   `composer lint:php` : checks all PHP files for syntax errors.
-   `composer make-pot` : generates a .pot file in the `languages/` directory.
