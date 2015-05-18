<?php namespace Cms\Modules\Docs\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseModuleController;
use League\CommonMark\CommonMarkConverter;

class PagesController extends BaseModuleController
{
    public $layout = '1-column';

    public function getIndex()
    {
        $path = storage_path('docs');
        $file = sprintf('%s/%s/index.md', $path, config('cms.docs.config.default'));

        if (!\File::exists($file)) {
            abort(404);
        }

        return $this->setView('_layout', [
            'contents' => with(new CommonMarkConverter)->convertToHtml($file),
        ], 'module');
    }

}
