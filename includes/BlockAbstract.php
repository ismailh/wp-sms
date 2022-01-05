<?php

namespace WP_SMS\Blocks;

use WP_Block;

class BlockAbstract
{
    /**
     * Whether block name
     *
     * @var $blockName
     */
    protected $blockName;

    /**
     * Widget class name
     *
     * @var $widgetClassName
     */
    protected $widgetClassName;

    /**
     * Front-end script
     *
     * @var bool $script
     */
    protected $script = false;

    /**
     * Block blockVersion
     *
     * @var $blockVersion
     */
    protected $blockVersion;

    /**
     * Register block type
     */
    public function registerBlockType()
    {
        $blockPath = "wp-statistics-widgets/{$this->blockName}";

        wp_register_script("wp-statistics-widgets-{$this->blockName}-script", Helper::getPluginAssetUrl("blocks/{$this->blockName}/index.js"), array('wp-blocks', 'wp-element', 'wp-editor'));
        wp_register_style("wp-statistics-widgets/{$this->blockName}-style", Helper::getPluginAssetUrl("blocks/{$this->blockName}/index.css"));

        register_block_type($blockPath, array(
            'render_callback' => [$this, 'renderCallback'],
            'editor_script'   => "wp-statistics-widgets-{$this->blockName}-script",
            'editor_style'    => "wp-statistics-widgets/{$this->blockName}-style",
        ));

    }

    /**
     * @param $attributes
     * @param $content
     * @param WP_Block $block
     * @return mixed
     */
    public function renderCallback($attributes, $content, WP_Block $block)
    {
        /**
         * Enqueue the script and data
         */
        if ($this->script) {
            wp_enqueue_script("wp-statistics-widgets-{$this->blockName}", Helper::getPluginAssetUrl($this->script), ['jquery'], $this->blockVersion, true);
            wp_localize_script("wp-statistics-widgets-{$this->blockName}", "{$this->blockName}Object", $this->getData($attributes));
        }

        /**
         * Render the output
         */
        return $this->output($attributes);
    }
}
