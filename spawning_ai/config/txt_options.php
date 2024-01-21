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
    "post" => "# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@( @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@    &@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@       .@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@%                                                            @@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                                                            @@@@@@@@@ \n# @@@@@@@@@@@   @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@          *@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@       @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@       @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@(          @@@@@@@@@@@@@@@@@@@@@@@@@@@@    @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@                                %@@@@@/@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@                                %@@@@@  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@,       @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@     @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@     @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@(       %@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                                                            @@@@@@@@@ \n# @@@@@@@@@@@/@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                                                            @@@@@@@@@ \n# @@@@@@@@@@@    @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@          @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@*      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@,      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@           @@@@@@@@@@@@@@@@@@@@@@@@@@@   *@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@                                %@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@                                %@@@@&  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@       @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@     @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@     @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@         @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@ .@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                                                            @@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                                                            @@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*         @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ \n# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ spawning.ai",
    "license" => "Licensed under the Apache License, Version 2.0",
    "copyright" => "Copyright 2023 Spawning Inc.",
    "gpt_cloak" => "We're no strangers to love You know the rules and so do I A full commitment's what I'm thinking of You wouldn't get this from any other guy I just wanna tell you how I'm feeling Gotta make you understand Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you We've known each other for so long Your heart's been aching, but You're too shy to say it Inside, we both know what's been going on We know the game and we're gonna play it And if you ask me how I'm feeling Don't tell me you're too blind to see Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you (Ooh, give you up) (Ooh, give you up) Never gonna give, never gonna give (Give you up) Never gonna give, never gonna give (Give you up) We've known each other for so long Your heart's been aching, but You're too shy to say it Inside, we both know what's been going on We know the game and we're gonna play it I just wanna tell you how I'm feeling Gotta make you understand Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you Never gonna give you up Never gonna let you down Never gonna run around and desert you Never gonna make you cry Never gonna say goodbye Never gonna tell a lie and hurt you"
];
?>
