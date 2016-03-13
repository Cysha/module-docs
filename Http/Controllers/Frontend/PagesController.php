<?php

namespace Cms\Modules\Docs\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Cms\Modules\Docs\Models\Documentation;

class PagesController extends BaseFrontendController
{
    public $layout = '1-column';

    protected $docs;

    public function boot()
    {
        parent::boot();

        $this->docs = app('Cms\Modules\Docs\Models\Documentation');
        $this->theme->prependTitle('Documentation | ');
        $this->theme->breadcrumb()->add('Documentation', route('pxcms.docs.index'));
        $this->theme->asset()->container('app')->add('prismjs-css', '/modules/docs/prismjs/prism.css');
        $this->theme->asset()->container('app')->add('prismjs-js', '/modules/docs/prismjs/prism.js');
    }

    public function getIndex()
    {
        return $this->show('index.md');
    }

    /**
     * Show a documentation page.
     *
     * @return Response
     */
    public function show($version, $page = null)
    {
        if (!$this->isVersion($version)) {
            return redirect('docs/'.config('cms.docs.config.default', 'master').'/'.$version, 302);
        }

        $section = '';
        if ($this->docs->sectionExists($version, $page)) {
            $section .= '/'.$page;
        } elseif (!is_null($page)) {
            return redirect('/docs/'.$version);
        }

        $content = $this->docs->get($version, $page ?: 'index', false);
        $title = $this->docs->setTitle($content);
        if (!empty($title)) {
            $this->theme->prependTitle($title.' | ');
            $this->theme->breadcrumb()->add($title, route('pxcms.docs.page', $version, $section));
        }

        if (is_null($content)) {
            abort(404);
        }

        $navigation = $this->docs->get($version, 'navigation', false);

        return $this->setView('_layout', [
            // 'index'          => $this->docs->getIndex($version),
            'contents' => $content,
            'nav' => $navigation,
        ]);
    }

    /**
     * Determine if the given URL segment is a valid version.
     *
     * @param string $version
     *
     * @return bool
     */
    protected function isVersion($version)
    {
        return in_array($version, config('cms.docs.config.versions'));
    }
}
