<?php

namespace Humweb\Pages;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Layouts
 *
 * @package Humweb\Pages
 */
class Layouts
{
    protected $layoutPaths;
    protected $themePath;
    protected $theme;


    /**
     * Layouts constructor.
     */
    public function __construct()
    {
        $this->setLayoutPaths(config('pages.layout_paths'));

        try {
            $this->theme         = resolve('theme');
            $this->themePath     = $this->theme->activeThemePath('views/layouts');
            $this->layoutPaths[] = $this->themePath;
        } catch (\Exception $e) {
        }
    }


    /**
     * @return mixed
     */
    public function getLayoutPaths()
    {
        return $this->layoutPaths;
    }


    /**
     * @param mixed $layoutPaths
     */
    public function setLayoutPaths($layoutPaths)
    {
        $this->layoutPaths = $layoutPaths;
    }


    public function lists()
    {
        //dd($this->layoutPaths);
        return Cache::remember('page:layouts6', 5, function () {
            $key   = '';
            $files = [];
            foreach ($this->layoutPaths as $prefix => $path) {
                if (strpos($prefix, '::') === false) {
                    $prefix = 'layouts.';
                }
                foreach (File::glob(rtrim($path, '/').'/*.blade.php') as $file) {
                    $key         = $this->prepareViewPath($prefix.basename($file));
                    $files[$key] = $key;
                }
            }

            return $files;
        });
    }


    public function prepareViewPath($string)
    {
        return str_replace('/', '.', str_replace('.blade.php', '', $string));
    }
}