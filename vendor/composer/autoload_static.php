<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite2446263cc6b8bee35aaa54843dd6da6
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spiritix\\Html2Pdf\\' => 18,
            'Spipu\\Html2Pdf\\' => 15,
        ),
        'E' => 
        array (
            'Eloquent\\Composer\\NpmBridge\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spiritix\\Html2Pdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/spiritix/php-chrome-html2pdf/src/Spiritix/Html2Pdf',
        ),
        'Spipu\\Html2Pdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/spipu/html2pdf/src',
        ),
        'Eloquent\\Composer\\NpmBridge\\' => 
        array (
            0 => __DIR__ . '/..' . '/eloquent/composer-npm-bridge/src',
        ),
    );

    public static $classMap = array (
        'Datamatrix' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/datamatrix.php',
        'PDF417' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/qrcode.php',
        'TCPDF' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_images.php',
        'TCPDF_IMPORT' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_import.php',
        'TCPDF_PARSER' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_parser.php',
        'TCPDF_STATIC' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_static.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite2446263cc6b8bee35aaa54843dd6da6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite2446263cc6b8bee35aaa54843dd6da6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite2446263cc6b8bee35aaa54843dd6da6::$classMap;

        }, null, ClassLoader::class);
    }
}
