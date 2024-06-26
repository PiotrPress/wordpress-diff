<?php declare( strict_types = 1 );

namespace PiotrPress\WordPress\Diff;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;

class Site {
    protected string $url = '';
    protected string $user = '';
    protected string $pass = '';

    public function __construct( string $url ) {
        $this->url = \parse_url( $url, \PHP_URL_SCHEME ) . '://' . \parse_url( $url, \PHP_URL_HOST );
        $this->user = \parse_url( $url, \PHP_URL_USER );
        $this->pass = \parse_url( $url, \PHP_URL_PASS );
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function getPosts( array $excludeTypes = [], array $excludePosts = [] ) : array {
        $types = \array_map( fn( $value ) => $value[ 'rest_base' ], $this->request( 'types' ) );
        $types = self::exclude( $types, [ 'revision', 'attachment', 'nav_menu_item', 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation', 'wp_font_family', 'wp_font_face' ] );
        $types = self::exclude( $types, $excludeTypes );

        $data = [];
        foreach( $types as $type => $endpoint ) {
            $data[ $type ] = [];
            $posts = $this->request( $endpoint );
            foreach( $posts as $post ) {
                $data[ $type ][ \parse_url( $post[ 'link' ], \PHP_URL_PATH ) ] = ( $post[ 'title' ][ 'rendered' ] ?? '' ) . "\n" . ( $post[ 'content' ][ 'rendered' ] ?? '' );
            }
        }

        return self::exclude( $data, \array_map( fn( $post ) => '/' === $post[ 0 ] ? $post : '/' . $post, $excludePosts ) );
    }

    protected function request( string $endpoint ) : array {
        $client = new Client( [ 'base_uri' => $this->url ] );
        $data = [];
        $page = 0;
        do {
            $response = $client->get( '/wp-json/wp/v2/' . $endpoint . '?per_page=100&page=' . ++$page, [
                'auth' => [ $this->user, $this->pass ],
                'verify' => false
            ] );
            $data = \array_merge( $data, Utils::jsonDecode( (string)$response->getBody(), true ) );
        } while( $response->hasHeader( 'X-WP-TotalPages' ) && $response->getHeader( 'X-WP-TotalPages' )[ 0 ] > $page );

        return $data;
    }

    public static function exclude( array $data, array $exclude ) : array {
        return \array_diff_key( $data, \array_flip( $exclude ) );
    }
}