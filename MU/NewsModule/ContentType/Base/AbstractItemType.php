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

namespace MU\NewsModule\ContentType\Base;

use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Zikula\Common\Content\AbstractContentType;
use MU\NewsModule\ContentType\Form\Type\ItemType as FormType;
use MU\NewsModule\Helper\ControllerHelper;

/**
 * Generic single item display content type base class.
 */
abstract class AbstractItemType extends AbstractContentType
{
    /**
     * @var ControllerHelper
     */
    protected $controllerHelper;
    
    /**
     * @var FragmentHandler
     */
    protected $fragmentHandler;
    
    /**
     * @inheritDoc
     */
    public function getIcon()
    {
        return 'circle-o';
    }
    
    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->translator->__('News detail', 'munewsmodule');
    }
    
    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->translator->__('Display or link a single news object.', 'munewsmodule');
    }
    
    /**
     * @inheritDoc
     */
    public function getDefaultData()
    {
        return [
            'objectType' => 'message',
            'id' => null,
            'displayMode' => 'embed',
            'customTemplate' => null
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $data = parent::getData();
    
        $contextArgs = ['name' => 'detail'];
        if (!isset($data['objectType']) || !in_array($data['objectType'], $this->controllerHelper->getObjectTypes('contentType', $contextArgs))) {
            $data['objectType'] = $this->controllerHelper->getDefaultObjectType('contentType', $contextArgs);
            $this->data = $data;
        }
    
        return $data;
    }
    
    /**
     * @inheritDoc
     */
    public function displayView()
    {
        if (null === $this->data['id'] || empty($this->data['id']) || empty($this->data['displayMode'])) {
            return '';
        }
    
        $controllerReference = new ControllerReference('MUNewsModule:External:display', $this->getDisplayArguments(), ['template' => $this->data['customTemplate']]);
    
        return $this->fragmentHandler->render($controllerReference, 'inline', []);
    }
    
    /**
     * @inheritDoc
     */
    public function displayEditing()
    {
        if (null === $this->data['id'] || empty($this->data['id']) || empty($this->data['displayMode'])) {
            return $this->translator->__('No item selected.', 'munewsmodule');
        }
    
        return parent::displayEditing();
    }
    
    /**
     * Returns common arguments for displaying the selected object using the external controller.
     *
     * @return array Display arguments
     */
    protected function getDisplayArguments()
    {
        return [
            'objectType' => $this->data['objectType'],
            'id' => $this->data['id'],
            'source' => 'contentType',
            'displayMode' => $this->data['displayMode']
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function getEditFormClass()
    {
        return FormType::class;
    }
    
    /**
     * @inheritDoc
     */
    public function getEditFormOptions($context)
    {
        $options = parent::getEditFormOptions($context);
        $data = $this->getData();
        $options['object_type'] = $data['objectType'];
    
        return $options;
    }
    
    /**
     * @param ControllerHelper $controllerHelper
     */
    public function setControllerHelper(ControllerHelper $controllerHelper)
    {
        $this->controllerHelper = $controllerHelper;
    }
    
    /**
     * @param FragmentHandler $fragmentHandler
     */
    public function setFragmentHandler(FragmentHandler $fragmentHandler)
    {
        $this->fragmentHandler = $fragmentHandler;
    }
}