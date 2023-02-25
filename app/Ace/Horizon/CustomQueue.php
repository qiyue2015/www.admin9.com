<?php

namespace App\Ace\Horizon;

class CustomQueue
{
    /**
     * 增量 archive queue.
     */
    public const ARCHIVE_INCREMENT_QUEUE = 'archive_increment';

    /**
     * 通过百度AI获取分类 queue （只能单线程）.
     */
    public const CATEGORY_UPDATE_QUEUE = 'category_update';

    /**
     * 通过百度AI提取简介（只能单线程）.
     */
    public const DESCRIPTION_UPDATE_QUEUE = 'description_update';

    /**
     * 增量 pixabay.com queue （只能单线程）.
     */
    public const PIXABAY_INCREMENT_QUEUE = 'pixabay_increment';

    /**
     * 大规模进程 queue.
     */
    public const LARGE_PROCESSES_QUEUE = 'large_processes';

    /**
     * 元语生成答案 queue.
     */
    public const CLUEAI_API_QUEUE = 'clueai_api';

}
