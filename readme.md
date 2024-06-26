# WordPress Diff

WordPress Diff is a command line tool for comparing contents of two WordPress installations using REST API and outputting the differences to HTML files for easy comparison.

## Example

```shell
$ php wp-diff https://oldsite.com https://newsite.com --exclude-type=post --exclude-post=sample-page
```

## Setup

```shell
$ docker compose up -d 
$ docker compose exec php bash -c "composer install"
$ docker compose down
```

## Usage

```shell
$ docker compose up -d
$ docker compose exec php bash -c "php wp-diff <old-site> <new-site> [--exclude-type=<post-type>] ... [--exclude-post=<post-link>] ..."
$ docker compose down
```

## Parameters 

The `<old-site>` and `<new-site>` are the URLs of the WordPress installations to compare. 

**NOTE:** URLs can include the authentication credentials in the form: `https://user:password@example.com`. 

The `--exclude-type` and `--exclude-post` options are used to exclude post types and posts from the comparison.

## Requirements

PHP ^`8.3` version.

## License

[MIT](license.txt)