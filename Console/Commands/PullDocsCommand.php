<?php

namespace Cms\Modules\Docs\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Process\Installer;
use Nwidart\Modules\Repository;

class PullDocsCommand extends Command
{
    protected $name = 'module:pull-docs';
    protected $readableName = 'Pulls the CMSs Documentation';
    protected $description = 'Pulls the CMSs Documentation';

    public function fire()
    {
        $files = app('Illuminate\Filesystem\Filesystem');

        $docPath = storage_path('docs');

        // make sure the directory is empty
        if (!$files->isDirectory($docPath)) {
            $files->makeDirectory($docPath);
        } else {
            $files->deleteDirectory($docPath);
            $files->makeDirectory($docPath);
        }

        // spawn the repository
        ob_start();
        $installer = new Installer(
            'Cysha/Documentation',
            null,
            'github',
            false
        );

        $installer->setPath($docPath.'/master');

        $repository = new Repository($this->laravel, $docPath);
        $installer->setRepository($repository);

        $installer->setConsole($this);

        $installer->run();
        ob_end_clean();
    }

    protected function getOptions()
    {
        return [
        ];
    }
}
