<?php namespace Cms\Modules\Docs\Models;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\DomCrawler\Crawler;

class Documentation
{

    /**
     * The filesystem implementation.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The cache implementation.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Create a new documentation instance.
     *
     * @param  Filesystem  $files
     * @param  Cache  $cache
     * @return void
     */
    public function __construct(Filesystem $files, Cache $cache)
    {
        $this->files = $files;
        $this->cache = $cache;
    }


    /**
     * Get the given documentation page.
     *
     * @param  string       $version
     * @param  string       $page
     * @param  int|bool     $cache
     * @return string
     */
    public function get($version, $page, $cache = 5)
    {
        $content = function () use ($version, $page) {
            $path = storage_path('docs/'.$version.'/'.$page.'.md');

            if ($this->files->exists($path)) {
                $content = $this->parseMarkdown($this->files->get($path));
                return $this->replaceLinks($version, $content);
            }

            return null;
        };

        if ($cache === false) {
            return value($content);
        }

        return $this->cache->remember('docs.'.$version.'.'.$page, $cache, $content);
    }

    /**
     * Sets the title for this page.
     *
     * @param string
     */
    public function setTitle($content)
    {
        $title = (new Crawler($content))->filterXPath('//h1');

        return count($title) ? $title->text() : null;
    }

    /**
     * Render the markdown in to HTML.
     *
     * @param  string  $version
     * @param  string  $content
     * @return string
     */
    protected function parseMarkdown($content)
    {
        return with(new CommonMarkConverter)->convertToHtml($content);
    }

    /**
     * Replace the version place-holder in links.
     *
     * @param  string  $version
     * @param  string  $content
     * @return string
     */
    protected function replaceLinks($version, $content)
    {
        return str_replace(['{{version}}', '%7B%7Bversion%7D%7D'], $version, $content);
    }

    /**
     * Check if the given section exists.
     *
     * @param  string  $version
     * @param  string  $page
     * @return boolean
     */
    public function sectionExists($version, $page)
    {
        return $this->files->exists(
            storage_path('docs/'.$version.'/'.$page.'.md')
        );
    }
}
