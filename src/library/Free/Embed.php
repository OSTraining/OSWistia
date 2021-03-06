<?php
/**
 * @package   OSWistia
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2016-2019 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSWistia.
 *
 * OSWistia is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSWistia is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSWistia.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Alledia\OSWistia\Free;

use Joomla\Registry\Registry;
use stdClass;

defined('_JEXEC') or die();

class Embed
{
    /**
     * The video id
     *
     * @var string
     */
    protected $videoId;

    /**
     * The embed params
     *
     * @var Registry
     */
    protected $params;

    public function __construct($videoId, $params)
    {
        $this->videoId = $videoId;
        $this->params  = $params;
    }

    /**
     * Create the Wistia embed code using the embed API.
     *
     * @return string
     */
    public function toString()
    {
        if (!empty($this->videoId)) {
            $width        = $this->params->get("width", 425);
            $height       = $this->params->get("height", 344);
            $shortVideoId = substr($this->videoId, 0, 3);
            $embedOptions = json_encode($this->getEmbedOptions());

            $class = array(
                'wistia_embed',
                'wistia_async_' . $this->videoId
            );

            if ($this->params->get('responsive', true)) {
                $class[] = 'videoFoam=true';
            }

            $attribs = array(
                sprintf('id="wistia_%s"', $this->videoId),
                sprintf('class="%s"', join(' ', $class)),
                sprintf('style="width:%spx; height:%spx;"', $width, $height)
            );

            $html = array(
                sprintf('<div %s></div>', join(' ', $attribs)),
                '<script src="//fast.wistia.com/assets/external/E-v1.js" async></script>',
                '<script>',
                "    window._wq = window._wq || [];",
                "    _wq.push({'{$shortVideoId}': {$embedOptions}});",
                "    _wq.push({id: '{$shortVideoId}', onReady: function(video) {window.wistiaEmbed = video; OSWistiaInit(video);}});",
                '</script>',
                "<script src=\""  . OSWISTIA_MEDIA_URL . "/js/oswistia.js\" async></script>\n",
            );

            return join("\n", $html);
        }

        return '';
    }

    /**
     * Return the embed options as object
     *
     * @return string
     */
    protected function getEmbedOptions()
    {
        $options = new stdClass;

        $options->videoFoam = (bool)$this->params->get('responsive', true);

        return $options;
    }
}
