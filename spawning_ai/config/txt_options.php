<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$txt_config = [
    "options" => [
        [
            "icon" => "image.svg",
            "label" => "Images",
            "value" => "*.bmp, *.gif, *.ico, *.jpeg, *.jpg, *.png, *.svg, *.tif, *.tiff, *.webp, *.eps, *.ai, *.indd, *.heif, *.raw, *.psd, *.cr2, *.nef, *.orf, *.sr2",
            "globalFlag" => false
        ],
        [
            "icon" => "audio.svg",
            "label" => "Audio",
            "value" => "*.aac, *.aiff, *.amr, *.flac, *.m4a, *.mp3, *.oga, *.opus, *.wav, *.wma, *.alac, *.dss, *.dvf, *.m4p, *.mmf, *.mpc, *.msv, *.ra, *.rm, *.tta, *.vox, *.wav, *.weba",
            "globalFlag" => false
        ],
        [
            "icon" => "video.svg",
            "label" => "Video",
            "value" => "*.mp4, *.webm, *.ogg, *.avi, *.mov, *.wmv, *.flv, *.mkv, *.3gp, *.3g2, *.h264, *.m4v, *.mpg, *.mpeg, *.rm, *.swf, *.vob, *.mts, *.m2ts, *.ts, *.qt, *.yuv, *.rmvb, *.asf, *.amv, *.mpg2",
            "globalFlag" => false
        ],
        [
            "icon" => "text.svg",
            "label" => "Text",
            "value" => "*.txt, *.pdf, *.doc, *.docx, *.odt, *.rtf, *.tex, *.wks, *.wpd, *.wps, *.html, *.htm, *.md, *.odf, *.xls, *.xlsx, *.ppt, *.pptx, *.csv, *.xml, *.ods, *.xlr, *.pages, *.log, *.key, *.odp",
            "globalFlag" => true
        ],
        [
            "icon" => "code.svg",
            "label" => "Code",
            "value" => "*.py, *.js, *.java, *.c, *.cpp, *.cs, *.h, *.css, *.php, *.swift, *.go, *.rb, *.pl, *.sh, *.sql, *.xml, *.json, *.ts, *.jsx, *.vue, *.r, *.kt, *.dart, *.rs, *.lua, *.asm, *.bash, *.erl, *.hs, *.vbs, *.bat, *.f, *.lisp, *.scala, *.groovy, *.ps1",
            "globalFlag" => false
        ],
    ],
    "userAgent" => "\nUser-agent: *\n",
    "allow" => "Allow: ",
    "disallow" => "Disallow: ",
    "globalDisallow" => "Disallow: *\nDisallow: /\n",
    "global" => "/\n",
    "pre" => "# Spawning AI\n",
    "post" => "",
    "license" => "Licensed under the Apache License, Version 2.0",
    "copyright" => "Copyright 2023 Spawning Inc."
];
?>
