<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * @category   Laminas
 * @package    LaminasDeveloperTools_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Laminas (https://www.zend.com)
 * @license    https://getlaminas.org/license/new-bsd     New BSD License
 */
class DetailArray extends AbstractHelper
{
    /**
     * Renders a detail entry for an array.
     *
     * @param  string  $label Label name
     * @param  array   $details Value array (list)
     * @param  boolean $redundant Marks this detail as redundant.
     * @return string
     */
    public function __invoke($label, array $details, $redundant = false)
    {
        $r = array();

        $r[] = '<span class="laminas-toolbar-info';
        $r[] = ($redundant) ? ' laminas-toolbar-info-redundant' : '';
        $r[] = '">';

        $r[] = '<span class="laminas-detail-label">';
        $r[] = $label;
        $r[] = '</span>';


        $extraCss = '';
        $newLine  = false;

        foreach ($details as $entry) {
            if ($newLine === true) {
                $r[] = '</span><span class="laminas-toolbar-info';
                $r[] = ($redundant) ? ' laminas-toolbar-info-redundant' : '';
                $r[] = '">';
            }

            $r[] = sprintf('<span class="laminas-detail-value%s">%s</span>', $extraCss, $entry);

            $newLine  = true;
            $extraCss = ' laminas-detail-extra-value';
        }

        $r[] = '</span>';

        return implode('', $r);
    }
}