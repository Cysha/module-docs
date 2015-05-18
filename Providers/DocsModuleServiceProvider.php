<?php namespace Cms\Modules\Docs\Providers;

use Cms\Modules\Core\Providers\BaseModuleProvider;

class DocsModuleServiceProvider extends BaseModuleProvider
{

    /**
     * Register the defined middleware.
     *
     * @var array
     */
    protected $middleware = [
        'Docs' => [
        ],
    ];

    /**
     * The commands to register.
     *
     * @var array
     */
    protected $commands = [
        'Docs' => [
        ],
    ];

}
