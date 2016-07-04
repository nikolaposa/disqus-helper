<?php
/**
 * This file is part of the Disqus Helper package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace DisqusHelper;

final class Code
{
    const INDENT = '    ';

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $scriptFiles = [];

    /**
     * @var string
     */
    private $htmlFragments;

    private function __construct()
    {
    }

    public static function create(string $shortName) : Code
    {
        $code = new self();

        $code->shortName = $shortName;

        return $code;
    }

    public function mergeConfig(array $config) : Code
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function getConfig() : array
    {
        return $this->config;
    }

    public function addScriptFile(string $fileName) : Code
    {
        if (!isset($this->scriptFiles[$fileName])) {
            $this->scriptFiles[$fileName] = [
                'fileName' => $fileName,
                'lazyLoad' => false,
            ];
        }

        return $this;
    }

    public function addLazyLoadedScriptFile(string $fileName) : Code
    {
        if (!isset($this->scriptFiles[$fileName])) {
            $this->scriptFiles[$fileName] = [
                'fileName' => $fileName,
                'lazyLoad' => true,
            ];
        }

        return $this;
    }

    public function hasScriptFile(string $fileName) : bool
    {
        return isset($this->scriptFiles[$fileName]);
    }

    public function __toString() : string
    {
        return $this->toHtml();
    }

    public function toHtml() : string
    {
        $this->htmlFragments = [];

        $this->buildConfigVariablesHtml();
        $this->buildJsFilesHtml();

        return implode(PHP_EOL . PHP_EOL, $this->htmlFragments);
    }

    private function buildConfigVariablesHtml()
    {
        if (empty($this->config)) {
            return;
        }

        $script = '<script>' . PHP_EOL;

        $script .= self::INDENT . 'var disqus_config = function () {' . PHP_EOL;

        foreach ($this->config as $key => $value) {
            if (is_string($value)) {
                $value = addslashes($value);
                $value = "'$value'";
            }
            $script .= self::INDENT . self::INDENT . "this.$key = $value;" . PHP_EOL;
        }

        $script .= self::INDENT . '};' . PHP_EOL ;

        $script .= '</script>';

        $this->htmlFragments[] = $script;
    }

    private function buildJsFilesHtml()
    {
        foreach ($this->scriptFiles as $fileInfo) {
            $fileName = $fileInfo['fileName'];

            if ($fileInfo['lazyLoad']) {
                $this->htmlFragments[] = $this->renderLazyLoadedJsFile($fileName);
                continue;
            }

            $this->htmlFragments[] = $this->renderJsFile($fileName);
        }
    }

    private function renderJsFile(string $fileName) : string
    {
        return sprintf('<script src="%s" async></script>', $this->getJsFileUrl($fileName));
    }

    private function renderLazyLoadedJsFile(string $fileName) : string
    {
        $script = '<script>' . PHP_EOL;
        $script .= self::INDENT . '(function() {' . PHP_EOL;
        $script .= self::INDENT . self::INDENT . 'var d = document, s = d.createElement("script");' . PHP_EOL;
        $script .= self::INDENT . self::INDENT . 's.src = "' . $this->getJsFileUrl($fileName) . '";' . PHP_EOL;
        $script .= self::INDENT . self::INDENT . 's.setAttribute("data-timestamp", +new Date());' . PHP_EOL;
        $script .= self::INDENT . self::INDENT . '(d.head || d.body).appendChild(s);' . PHP_EOL;
        $script .= self::INDENT . '})();' . PHP_EOL;
        $script .= '</script>';

        return $script;
    }

    private function getJsFileUrl(string $fileName)
    {
        return '//' . $this->shortName . '.disqus.com/' . $fileName;
    }
}