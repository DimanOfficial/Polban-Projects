<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    // app/Config/Pager.php
    public array $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'pagination_temp' => 'App\Views\Pagers\kegiatan_pagination',
    ];

    
    public $default_full = [
        'template' => 'CodeIgniter\Pager\Views\default_full',
        'perPage'  => 5,
    ];

    public $default = [
        'page' => 1,
        'perPage' => 3,
        'numLinks' => 3,
        'usePageNumbers' => true,
        'fullTagOpen' => '<div class="pagination">',
        'fullTagClose' => '</div>',
        'firstLink' => 'First',
        'lastLink' => 'Last',
        'nextLink' => 'Next',
        'prevLink' => 'Previous',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;
}
