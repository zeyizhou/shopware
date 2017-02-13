<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\CustomerSearchBundle\ConditionHandler;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\CustomerSearchBundle\Condition\OrderedWithPaymentCondition;
use Shopware\Bundle\CustomerSearchBundle\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundle\ConditionInterface;

class OrderedWithPaymentConditionHandler implements ConditionHandlerInterface
{
    public function supports(ConditionInterface $condition)
    {
        return $condition instanceof OrderedWithPaymentCondition;
    }

    public function handle(ConditionInterface $condition, QueryBuilder $query)
    {
        $query->innerJoin(
            'customer',
            's_order',
            'orderedWithPayment',
            'orderedWithPayment.userID = customer.id
             AND orderedWithPayment.paymentID IN (:OrderedWithPaymentCondition)'
        );

        /** @var OrderedWithPaymentCondition $condition */
        $query->setParameter(':OrderedWithPaymentCondition', $condition->getPaymentIds(), Connection::PARAM_INT_ARRAY);
    }
}