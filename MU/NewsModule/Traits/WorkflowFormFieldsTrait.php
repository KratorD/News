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

namespace MU\NewsModule\Traits;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Workflow form fields trait implementation class.
 */
trait WorkflowFormFieldsTrait
{
    /**
     * Adds a field for additional notification remarks.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options
     */
    public function addAdditionalNotificationRemarksField(FormBuilderInterface $builder, array $options = [])
    {
        $helpText = '';
        if ($options['is_moderator']) {
            $helpText = $this->__('These remarks (like a reason for deny) are not stored, but added to any notification emails send to the creator.');
        } elseif ($options['is_creator']) {
            $helpText = $this->__('These remarks (like questions about conformance) are not stored, but added to any notification emails send to our moderators.');
        }
    
        $builder->add('additionalNotificationRemarks', TextareaType::class, [
            'mapped' => false,
            'label' => $this->__('Additional remarks'),
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $helpText
            ],
            'attr' => [
                'title' => 'create' == $options['mode'] ? $this->__('Enter any additions about your content') : $this->__('Enter any additions about your changes')
            ],
            'required' => false,
            'help' => $helpText
        ]);
    }
}
