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

    public static function create() : Code
    {
        return new self();
    }

    public function setConfigVariable(string $key, $value) : Code
    {
        $this->config[$key] = $value;

        return $this;
    }

    public function mergeConfig(array $config) : Code
    {
        $this->config = array_merge($this->config, $config);

        return $this;
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

        $this->html = '<script type="text/javascript">';
        $this->html .= PHP_EOL;

        $this->renderConfigVariables();

        $this->html .= PHP_EOL . PHP_EOL;

        $this->renderJsFiles();

        $this->html .= PHP_EOL . PHP_EOL;
        $this->html .= '</script>';

        return $this->html;
    }

    private function renderConfigVariables()
    {
        $configVars = [];

        foreach ($this->config as $key => $value) {
            if (is_string($value)) {
                $value = addslashes((string) $value);
                $value = "'$value'";
            }
            $configVars[] = self::INDENT . "var disqus_$key = $value;";
        }

        $this->html .= implode(PHP_EOL, $configVars);
    }

    private function renderJsFiles()
    {
        foreach ($this->scriptFiles as $fileName) {
            $this->html .= self::INDENT . '(function() {' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 'var s = document.createElement("script");' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 's.type = "text/javascript";' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 's.async = true;' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . 's.src = "//" + disqus_shortname + ".disqus.com/' . $fileName . '";' . PHP_EOL;
            $this->html .= self::INDENT . self::INDENT . '(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(s);' . PHP_EOL;
            $this->html .= self::INDENT . '})();' . PHP_EOL . PHP_EOL;
        }

        $this->html = rtrim($this->html, PHP_EOL);
    }
}