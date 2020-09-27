<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 4:57 PM
 */

namespace common\symbol;


class AdditionalSymbol extends BaseSymbol
{
    const TYPE_IMG = 1;
    const TYPE_TEXT = 2;
    const TYPE_IMGS = 3;

//    key text
    const ABOUT_TOP_BANNER = 'about:top:banner';

    const ABOUT_BOTTOM_IMG = 'about:bottom:img';

    const JOB_MAIL = 'job:mail';

    const JOB_TEL = 'job:tel';

    const CONTACT_ADDRESS = 'contact:address';

    const CONTACT_TEL = 'contact:tel';

    const CONTACT_MAIL = 'contact:mail';

    const CONTACT_QR = 'contact:qr';

    public static $map = [
        self::TYPE_IMG => '图片',
        self::TYPE_TEXT => '文本',
        self::TYPE_IMGS => '多图'
    ];
}