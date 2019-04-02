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

namespace MU\NewsModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\Entity\AbstractEntityAttribute;
use MU\NewsModule\Entity\MessageEntity;

/**
 * Entity extension domain class storing message attributes.
 *
 * This is the base attribute class for message entities.
 */
abstract class AbstractMessageAttributeEntity extends AbstractEntityAttribute
{
    /**
     * @ORM\ManyToOne(targetEntity="\MU\NewsModule\Entity\MessageEntity", inversedBy="attributes")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var MessageEntity
     */
    protected $entity;
    
    /**
     * Returns the entity.
     *
     * @return MessageEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Sets the entity.
     *
     * @param MessageEntity $entity
     *
     * @return void
     */
    public function setEntity($entity)
    {
        if ($this->entity !== $entity) {
            $this->entity = isset($entity) ? $entity : '';
        }
    }
    
}
