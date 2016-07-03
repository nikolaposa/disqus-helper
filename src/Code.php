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
    private $html;

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
            $this->scriptFiles[$fileName] = $fileName;
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
        $this->html = '';

        $this->html = '<script>';
        $this->html .= PHP_EOL;

        $this->renderConfigVariables();
        $this->renderJsFiles();

        $this->html = rtrim($this->html, PHP_EOL);
        $this->html .= PHP_EOL;
        $this->html .= '</script>';

        return $this->html;
    }

    private function renderConfigVariables()
    {
        if (empty($this->config)) {
            return;
        }

        $this->html .= self::INDENT . 'var disqus_config = function () {' . PHP_EOL;

        foreach ($this->config as $key => $value) {
            if (is_string($value)) {
                $value = addslashes($value);
                $value = "'$value'";
            }
            $this->html .= self::INDENT . self::INDENT . "this.$key = $value;" . PHP_EOL;
        }

        $this->html .= self::INDENT . '};' . PHP_EOL . PHP_EOL;
    }

    private function renderJsFiles()
    {
        foreach ($this->scriptFiles as $fileName) {
            $this->html .= self::INDENT . '(function() {' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 'var d = document, s = d.createElement("script");' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 's.src = "//' . $this->shortName . '.disqus.com/' . $fileName . '";' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 's.setAttribute("data-timestamp", +new Date());' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . '(d.head || d.body).appendChild(s);' . PHP_EOL;
            $this->html .= self::INDENT . '})();' . PHP_EOL . PHP_EOL;
        }
    }
}