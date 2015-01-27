<?php

namespace Tisseo\DatawarehouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\DatawarehouseBundle\Form\Type\CustomerType;

/*
 * CalendarController
 */
class CustomerController extends AbstractController
{
    public function listAction($externalNetworkId)
    {
        $this->isGranted('BUSINESS_MANAGE_CUSTOMER');
        $customerManager = $this->get('sam_core.customer');
        $customerApplications = $customerManager->findByCurrentApp();
        $customers = array();

        foreach ($customerApplications as $customerApplication) {
            $customer = $customerManager->find($customerApplication->getCustomer());
            $customers[] = $customer;
        }

        return $this->render(
            'TisseoDatawarehouseBundle:Customer:list.html.twig',
            array(
                'externalNetworkId' => $externalNetworkId,
                'customers' => $customers
            )
        );
    }
}
