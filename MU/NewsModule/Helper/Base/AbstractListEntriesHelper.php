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

use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;

/**
 * Helper base class for list field entries related methods.
 */
abstract class AbstractListEntriesHelper
{
    use TranslatorTrait;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }
    
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * Return the name or names for a given list item.
     *
     * @param string $value The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName The list field's name
     * @param string $delimiter String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function resolve($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && '0' !== $value) || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        $isMulti = $this->hasMultipleSelection($objectType, $fieldName);
        $values = $isMulti ? $this->extractMultiList($value) : [];
    
        $options = $this->getEntries($objectType, $fieldName);
        $result = '';
    
        if (true === $isMulti) {
            foreach ($options as $option) {
                if (!in_array($option['value'], $values, true)) {
                    continue;
                }
                if (!empty($result)) {
                    $result .= $delimiter;
                }
                $result .= $option['text'];
            }
        } else {
            foreach ($options as $option) {
                if ($option['value'] !== $value) {
                    continue;
                }
                $result = $option['text'];
                break;
            }
        }
    
        return $result;
    }
    
    
    /**
     * Extract concatenated multi selection.
     *
     * @param string $value The dropdown value to process
     *
     * @return string[] List of single values
     */
    public function extractMultiList($value)
    {
        $listValues = explode('###', $value);
        $amountOfValues = count($listValues);
        if ($amountOfValues > 1 && '' === $listValues[$amountOfValues - 1]) {
            unset($listValues[$amountOfValues - 1]);
        }
        if ('' === $listValues[0]) {
            // use array_shift instead of unset for proper key reindexing
            // keys must start with 0, otherwise the dropdownlist form plugin gets confused
            array_shift($listValues);
        }
    
        return $listValues;
    }
    
    
    /**
     * Determine whether a certain dropdown field has a multi selection or not.
     *
     * @param string $objectType The treated object type
     * @param string $fieldName The list field's name
     *
     * @return bool True if this is a multi list false otherwise
     */
    public function hasMultipleSelection($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return false;
        }
    
        $result = false;
        switch ($objectType) {
            case 'message':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'image':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'appSettings':
                switch ($fieldName) {
                    case 'defaultMessageSorting':
                        $result = false;
                        break;
                    case 'defaultMessageSortingBackend':
                        $result = false;
                        break;
                    case 'sortingDirection':
                        $result = false;
                        break;
                    case 'imageFloatOnViewPage':
                        $result = false;
                        break;
                    case 'imageFloatOnDisplayPage':
                        $result = false;
                        break;
                    case 'thumbnailModeMessageImageUpload1':
                        $result = false;
                        break;
                    case 'thumbnailModeMessageImageUpload2':
                        $result = false;
                        break;
                    case 'thumbnailModeMessageImageUpload3':
                        $result = false;
                        break;
                    case 'thumbnailModeMessageImageUpload4':
                        $result = false;
                        break;
                    case 'thumbnailModeImageTheFile':
                        $result = false;
                        break;
                    case 'enabledFinderTypes':
                        $result = true;
                        break;
                }
                break;
        }
    
        return $result;
    }
    
    
    /**
     * Get entries for a certain dropdown field.
     *
     * @param string $objectType The treated object type
     * @param string $fieldName The list field's name
     *
     * @return array Array with desired list entries
     */
    public function getEntries($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return [];
        }
    
        $entries = [];
        switch ($objectType) {
            case 'message':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForMessage();
                        break;
                }
                break;
            case 'image':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForImage();
                        break;
                }
                break;
            case 'appSettings':
                switch ($fieldName) {
                    case 'defaultMessageSorting':
                        $entries = $this->getDefaultMessageSortingEntriesForAppSettings();
                        break;
                    case 'defaultMessageSortingBackend':
                        $entries = $this->getDefaultMessageSortingBackendEntriesForAppSettings();
                        break;
                    case 'sortingDirection':
                        $entries = $this->getSortingDirectionEntriesForAppSettings();
                        break;
                    case 'imageFloatOnViewPage':
                        $entries = $this->getImageFloatOnViewPageEntriesForAppSettings();
                        break;
                    case 'imageFloatOnDisplayPage':
                        $entries = $this->getImageFloatOnDisplayPageEntriesForAppSettings();
                        break;
                    case 'thumbnailModeMessageImageUpload1':
                        $entries = $this->getThumbnailModeMessageImageUpload1EntriesForAppSettings();
                        break;
                    case 'thumbnailModeMessageImageUpload2':
                        $entries = $this->getThumbnailModeMessageImageUpload2EntriesForAppSettings();
                        break;
                    case 'thumbnailModeMessageImageUpload3':
                        $entries = $this->getThumbnailModeMessageImageUpload3EntriesForAppSettings();
                        break;
                    case 'thumbnailModeMessageImageUpload4':
                        $entries = $this->getThumbnailModeMessageImageUpload4EntriesForAppSettings();
                        break;
                    case 'thumbnailModeImageTheFile':
                        $entries = $this->getThumbnailModeImageTheFileEntriesForAppSettings();
                        break;
                    case 'enabledFinderTypes':
                        $entries = $this->getEnabledFinderTypesEntriesForAppSettings();
                        break;
                }
                break;
        }
    
        return $entries;
    }
    
    
    /**
     * Get 'workflow state' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getWorkflowStateEntriesForMessage()
    {
        $states = [];
        $states[] = [
            'value'   => 'waiting',
            'text'    => $this->__('Waiting'),
            'title'   => $this->__('Content has been submitted and waits for approval.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->__('Approved'),
            'title'   => $this->__('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'suspended',
            'text'    => $this->__('Suspended'),
            'title'   => $this->__('Content has been approved, but is temporarily offline.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'archived',
            'text'    => $this->__('Archived'),
            'title'   => $this->__('Content has reached the end and became archived.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->__('Trashed'),
            'title'   => $this->__('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!waiting',
            'text'    => $this->__('All except waiting'),
            'title'   => $this->__('Shows all items except these which are waiting'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->__('All except approved'),
            'title'   => $this->__('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!suspended',
            'text'    => $this->__('All except suspended'),
            'title'   => $this->__('Shows all items except these which are suspended'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!archived',
            'text'    => $this->__('All except archived'),
            'title'   => $this->__('Shows all items except these which are archived'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->__('All except trashed'),
            'title'   => $this->__('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'workflow state' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getWorkflowStateEntriesForImage()
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->__('Approved'),
            'title'   => $this->__('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->__('Trashed'),
            'title'   => $this->__('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->__('All except approved'),
            'title'   => $this->__('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->__('All except trashed'),
            'title'   => $this->__('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'default message sorting' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getDefaultMessageSortingEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'articleID',
            'text'    => $this->__('ArticleID'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'articledatetime',
            'text'    => $this->__('ArticleDateTime'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'articlestartdate',
            'text'    => $this->__('ArticleStartDate'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'articleweight',
            'text'    => $this->__('ArticleWeight'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'default message sorting backend' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getDefaultMessageSortingBackendEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'articleID',
            'text'    => $this->__('ArticleID'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'articledatetime',
            'text'    => $this->__('ArticleDateTime'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'articlestartdate',
            'text'    => $this->__('ArticleStartDate'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'articleweight',
            'text'    => $this->__('ArticleWeight'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'sorting direction' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getSortingDirectionEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'descending',
            'text'    => $this->__('Descending'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'ascending',
            'text'    => $this->__('Ascending'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'image float on view page' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getImageFloatOnViewPageEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'left',
            'text'    => $this->__('Left'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'right',
            'text'    => $this->__('Right'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'image float on display page' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getImageFloatOnDisplayPageEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'left',
            'text'    => $this->__('Left'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'right',
            'text'    => $this->__('Right'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'thumbnail mode message image upload 1' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getThumbnailModeMessageImageUpload1EntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'inset',
            'text'    => $this->__('Inset'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'outbound',
            'text'    => $this->__('Outbound'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'thumbnail mode message image upload 2' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getThumbnailModeMessageImageUpload2EntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'inset',
            'text'    => $this->__('Inset'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'outbound',
            'text'    => $this->__('Outbound'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'thumbnail mode message image upload 3' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getThumbnailModeMessageImageUpload3EntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'inset',
            'text'    => $this->__('Inset'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'outbound',
            'text'    => $this->__('Outbound'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'thumbnail mode message image upload 4' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getThumbnailModeMessageImageUpload4EntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'inset',
            'text'    => $this->__('Inset'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'outbound',
            'text'    => $this->__('Outbound'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'thumbnail mode image the file' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getThumbnailModeImageTheFileEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'inset',
            'text'    => $this->__('Inset'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'outbound',
            'text'    => $this->__('Outbound'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'enabled finder types' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getEnabledFinderTypesEntriesForAppSettings()
    {
        $states = [];
        $states[] = [
            'value'   => 'message',
            'text'    => $this->__('Message'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
        $states[] = [
            'value'   => 'image',
            'text'    => $this->__('Image'),
            'title'   => '',
            'image'   => '',
            'default' => true
        ];
    
        return $states;
    }
}
