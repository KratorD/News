<?php
/**
 * News.
*
* @copyright Michael Ueberschaer (MU)
* @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
* @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
* @link https://homepages-mit-zikula.de
* @link http://zikula.org
* @version Generated by ModuleStudio (https://modulestudio.de).
*/

namespace MU\NewsModule\Helper;

use MU\NewsModule\Helper\Base\AbstractControllerHelper;


use Symfony\Component\HttpFoundation\Request;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\RouteUrl;
use Zikula\UsersModule\Entity\UserEntity;
use MU\NewsModule\Entity\Factory\EntityFactory;
use MU\NewsModule\Helper\FeatureActivationHelper;
use MU\NewsModule\Helper\ModelHelper;

/**
 * Helper implementation class for controller layer methods.
 */
class ControllerHelper extends AbstractControllerHelper
{
    /**
     * Processes the parameters for a view action.
     * This includes handling pagination, quick navigation forms and other aspects.
     *
     * @param string          $objectType         Name of treated entity type
     * @param SortableColumns $sortableColumns    Used SortableColumns instance
     * @param array           $templateParameters Template data
     * @param boolean         $hasHookSubscriber  Whether hook subscribers are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
	public function processViewActionParameters($objectType, SortableColumns $sortableColumns, array $templateParameters = [], $hasHookSubscriber = false)
	{
		$contextArgs = ['controller' => $objectType, 'action' => 'view'];
		if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
			throw new \Exception($this->__('Error! Invalid object type received.'));
		}

		die('T');
	
		$request = $this->request;
		$repository = $this->entityFactory->getRepository($objectType);
	
		// parameter for used sorting field
		$sort = $request->query->get('sort', '');
		if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
			$sort = $repository->getDefaultSortingField();
			
			$request->query->set('sort', $sort);
			// set default sorting in route parameters (e.g. for the pager)
			$routeParams = $request->attributes->get('_route_params');
			$routeParams['sort'] = $sort;
			$request->attributes->set('_route_params', $routeParams);
		}
		$sortdir = $request->query->get('sortdir', 'ASC');
		if (false !== strpos($sort, ' DESC')) {
			$sort = str_replace(' DESC', '', $sort);
			$sortdir = 'desc';
		}
		$templateParameters['sort'] = $sort;
		$templateParameters['sortdir'] = strtolower($sortdir);
	
		$templateParameters['all'] = 'csv' == $request->getRequestFormat() ? 1 : $request->query->getInt('all', 0);
		$templateParameters['own'] = $request->query->getInt('own', $this->variableApi->get('MUNewsModule', 'showOnlyOwnEntries', 0));
	
		$resultsPerPage = 0;
		if ($templateParameters['all'] != 1) {
			// the number of items displayed on a page for pagination
			$resultsPerPage = $request->query->getInt('num', 0);
			if (in_array($resultsPerPage, [0, 10])) {
				$resultsPerPage = $this->variableApi->get('MUNewsModule', $objectType . 'EntriesPerPage', 10);
			}
		}
		$templateParameters['num'] = $resultsPerPage;
		$templateParameters['tpl'] = $request->query->getAlnum('tpl', '');
	
		$templateParameters = $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
	
		$quickNavForm = $this->formFactory->create('MU\NewsModule\Form\Type\QuickNavigation\\' . ucfirst($objectType) . 'QuickNavType', $templateParameters);
		if ($quickNavForm->handleRequest($request) && $quickNavForm->isSubmitted()) {
			$quickNavData = $quickNavForm->getData();
			foreach ($quickNavData as $fieldName => $fieldValue) {
				if ($fieldName == 'routeArea') {
					continue;
				}
				if (in_array($fieldName, ['all', 'own', 'num'])) {
					$templateParameters[$fieldName] = $fieldValue;
				} elseif ($fieldName == 'sort' && !empty($fieldValue)) {
					$sort = $fieldValue;
				} elseif ($fieldName == 'sortdir' && !empty($fieldValue)) {
					$sortdir = $fieldValue;
				} elseif (false === stripos($fieldName, 'thumbRuntimeOptions') && false === stripos($fieldName, 'featureActivationHelper')) {
					// set filter as query argument, fetched inside repository
					if ($fieldValue instanceof UserEntity) {
						$fieldValue = $fieldValue->getUid();
					}
					$request->query->set($fieldName, $fieldValue);
				}
			}
		}
		$sortableColumns->setOrderBy($sortableColumns->getColumn($sort), strtoupper($sortdir));
		$resultsPerPage = $templateParameters['num'];
		$request->query->set('own', $templateParameters['own']);
	
		$urlParameters = $templateParameters;
		foreach ($urlParameters as $parameterName => $parameterValue) {
			if (false === stripos($parameterName, 'thumbRuntimeOptions')
					&& false === stripos($parameterName, 'featureActivationHelper')
					) {
						continue;
					}
					unset($urlParameters[$parameterName]);
		}
	
		$sort = $sortableColumns->getSortColumn()->getName();
		$sortdir = $sortableColumns->getSortDirection();
		$sortableColumns->setAdditionalUrlParameters($urlParameters);
	
		$where = '';
		if ($templateParameters['all'] == 1) {
			// retrieve item list without pagination
			$entities = $repository->selectWhere($where, $sort . ' ' . $sortdir);
		} else {
			// the current offset which is used to calculate the pagination
			$currentPage = $request->query->getInt('pos', 1);
	
			// retrieve item list with pagination
			list($entities, $objectCount) = $repository->selectWherePaginated($where, $sort . ' ' . $sortdir, $currentPage, $resultsPerPage);
	
			$templateParameters['currentPage'] = $currentPage;
			$templateParameters['pager'] = [
					'amountOfItems' => $objectCount,
					'itemsPerPage' => $resultsPerPage
			];
		}
	
		$templateParameters['sort'] = $sort;
		$templateParameters['sortdir'] = $sortdir;
		$templateParameters['items'] = $entities;
	
		if (true === $hasHookSubscriber) {
			// build RouteUrl instance for display hooks
			$urlParameters['_locale'] = $request->getLocale();
			$templateParameters['currentUrlObject'] = new RouteUrl('munewsmodule_' . strtolower($objectType) . '_view', $urlParameters);
		}
	
		$templateParameters['sort'] = $sortableColumns->generateSortableColumns();
		$templateParameters['quickNavForm'] = $quickNavForm->createView();
	
		$templateParameters['canBeCreated'] = $this->modelHelper->canBeCreated($objectType);
	
		$request->query->set('sort', $sort);
		$request->query->set('sortdir', $sortdir);
		// set current sorting in route parameters (e.g. for the pager)
		$routeParams = $request->attributes->get('_route_params');
		$routeParams['sort'] = $sort;
		$routeParams['sortdir'] = $sortdir;
		$request->attributes->set('_route_params', $routeParams);
	
		return $templateParameters;
	}
}
