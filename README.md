# Simple webpack WP theme workflow based on @wordpress/scripts (https://www.npmjs.com/package/@wordpress/scripts)

Find/replace `themename` to your theme name

1. `npm install`
2. for development: `npm start`
3. for production: `npm run build`
4. for archiving pure theme to zip: `npm run bundle`

### Composer stuff (I don't use)

```sh
$ composer install
```

-   `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   `composer lint:php` : checks all PHP files for syntax errors.
-   `composer make-pot` : generates a .pot file in the `languages/` directory.
