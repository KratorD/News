<?php
/**
 * News.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\NewsModule\Helper\Base;

/**
 * Helper base class for dynamic feature enablement methods.
 */
abstract class AbstractFeatureActivationHelper
{
    /**
     * Categorisation feature
     */
    const CATEGORIES = 'categories';
    
    /**
     * Attribution feature
     */
    const ATTRIBUTES = 'attributes';
    
    /**
     * Translation feature
     */
    const TRANSLATIONS = 'translations';
    
    /**
     * This method checks whether a certain feature is enabled for a given entity type or not.
     *
     * @param string $feature     Name of requested feature
     * @param string $objectType  Currently treated entity type
     *
     * @return boolean True if the feature is enabled, false otherwise
     */
    public function isEnabled($feature, $objectType)
    {
        if (self::CATEGORIES == $feature) {
            $method = 'hasCategories';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['message']);
        }
        if (self::ATTRIBUTES == $feature) {
            $method = 'hasAttributes';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['message']);
        }
        if (self::TRANSLATIONS == $feature) {
            $method = 'hasTranslations';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['message']);
        }
    
        return false;
    }
}
