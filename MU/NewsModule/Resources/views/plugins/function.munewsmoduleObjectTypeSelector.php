<?php
/**
 * News.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepage-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio 1.0.1 (https://modulestudio.de).
 */

/**
 * The munewsmoduleObjectTypeSelector plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params All attributes passed to this function from the template
 * @param  Zikula_Form_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_munewsmoduleObjectTypeSelector($params, $view)
{
    $dom = ZLanguage::getModuleDomain('MUNewsModule');
    $result = [];

    $result[] = [
        'text' => __('Messages', $dom),
        'value' => 'message'
    ];

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);

        return;
    }

    return $result;
}