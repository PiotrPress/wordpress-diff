<?php declare( strict_types = 1 );

namespace PiotrPress\WordPress\Diff;

use Jfcherng\Diff\DiffHelper;

class Diff {
    public function __construct(
        protected string $oldContent = '',
        protected string $newContent = '' ) {}

    public function getHTML() : string {
        $html = '<style>' . DiffHelper::getStyleSheet() . '</style>';
        $html .= DiffHelper::calculate(
            self::clean( $this->oldContent ),
            self::clean( $this->newContent ),
            'SideBySide',
            [],
            [ 'detailLevel' => 'char' ],
        );

        return $html;
    }

    public static function clean( string $content = '' ) : string {
        return \preg_replace("/[\n]+/", "\n", \strip_tags( \str_replace( [ '<br>', '<br/>', '<br />' ], "\n", $content ) ) ) ?? '';
    }
}