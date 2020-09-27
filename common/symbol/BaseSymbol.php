<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 2:29 PM
 */

namespace common\symbol;

class BaseSymbol
{
    const STATUS_NORMAL = 0;
    const STATUS_DELETE = 1;
    const STATUS_DRAFT = 2;

    const STATUS_MAP = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_DRAFT => '草稿箱',
        self::STATUS_DELETE => '删除'
    ];
}