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

namespace MU\NewsModule\Twig;

use MU\NewsModule\Twig\Base\AbstractTwigExtension;
use MU\NewsModule\Entity\Factory\EntityFactory;
use MU\NewsModule\Entity\MessageCategoryEntity;
use MU\NewsModule\Helper\CategoryHelper;

/**
 * Twig extension implementation class.
 */
class TwigExtension extends AbstractTwigExtension
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * Sets the entity factory.
     *
     * @param EntityFactory $entityFactory
     */
    public function setEntityFactory(EntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * Sets the category helper.
     *
     * @param CategoryHelper $categoryHelper
     */
    public function setCategoryHelper(CategoryHelper $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }
    
    /**
     * Returns a list of custom Twig functions.
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions[] = new \Twig_SimpleFunction('munewsmodule_getRelatedArticles', [$this, 'relatedArticles']);
        $functions[] = new \Twig_SimpleFunction('munewsmodule_setAmount', [$this, 'setAmount']);

        return $functions;
    }
    
    /**
     * Selects and returns related articles.
     *
     * @param MessageCategoryEntity $catMapping given category mapping
     * @param int $amount The amount of articles to fetch
     *
     * @return array
     */
    public function relatedArticles(MessageCategoryEntity $catMapping, $amount = 3) {
        $articles = [];

        $propertyMapping = $this->categoryHelper->getAllProperties('message');
        $propertyName = '';
        $categoryRegistryId = $catMapping->getCategoryRegistryId();
        foreach ($propertyMapping as $propName => $regId) {
            if ($regId == $categoryRegistryId) {
                $propertyName = $propName;
                break;
            }
        }
        if ('' == $propertyName) {
            return $articles;
        }

        $repository = $this->entityFactory->getRepository('message');
        $qb = $repository->getListQueryBuilder();

        $categoryId = $catMapping->getCategory()->getId();
        $catIds = [$propName => [$categoryId]];

        $this->categoryHelper->buildFilterClauses($qb, 'message', $catIds);

        $qb->andWhere('tbl.id != :excludedIdentifier')
           ->setParameter('excludedIdentifier', $catMapping->getEntity()->getId());

        $qb->orderBy('tbl.createdDate', 'DESC');

        $query = $repository->getQueryFromBuilder($qb);

        $query->setMaxResults($amount);
        
        return $repository->retrieveCollectionResult($query, false);
    }
    
    /**
     * set amount of view one higher
     * 
     * @param object $message entity in the template
     * @param string $routeArea backend or not
     */
    public function setAmount($message, $routeArea) {
    	if ($routeArea != 'admin') {
    	 $message->setAmountOfViews($message->getAmountOfViews() + 1);
    	 $this->workflowHelper->executeAction($message, 'update');
    	 }
    }
}
