<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:01
 */
namespace Magestore\Affiliatepluslevel\Model;

use Magento\Framework\App\Area;
/**
 * Class Transaction
 * @package Magestore\Affiliatepluslevel\Model
 */
class Transaction extends AbstractModel
{
    /**
     * General email identify path
     */
    const XML_PATH_ADMIN_EMAIL_IDENTITY = 'trans_email/ident_general';

    /**
     * Sales email identify path
     */
    const XML_PATH_EMAIL_IDENTITY = 'trans_email/ident_sales';

    /**
     * New transaction email template that will be sent to affiliate
     */
    const XML_PATH_NEW_TRANSACTION_ACCOUNT_EMAIL = 'affiliateplus/email/multilevel_new_transaction_account_email_template';
    /**
     * Updated transaction email template that will be sent to affiliate
     */
    const XML_PATH_UPDATED_TRANSACTION_ACCOUNT_EMAIL = 'affiliateplus/email/multilevel_updated_transaction_account_email_template';
    /**
     * Reduced transaction email template that will be sent to affiliate
     */
    const XML_PATH_REDUCED_TRANSACTION_ACCOUNT_EMAIL = 'affiliateplus/email/multilevel_reduce_commission_account_email_template';
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction');
    }
    /**
     * send email new transaction to affiliate tier
     *
     * @return void
     */
    public function sendMailNewTransactionToAccount($transaction){
        if (!$this->_helperConfig->getEmailConfig('multilevel_is_sent_email_account_new_transaction')){
            return $this;
        }
        $store = $this->_storeManager->getStore($transaction->getStoreId());
        $currentCurrency = $store->getCurrentCurrency();
        $store->setCurrentCurrency($store->getBaseCurrency());
        $account = $this->_accountFactory->create()->load($this->getTierId());

        $this->setProducts($this->_helper->getFrontendProductHtmls($transaction->getOrderItemIds()))
            ->setTotalAmountFormated($this->_helper->convertCurrency($transaction->getTotalAmount()))
            ->setCommissionFormated($this->_helper->convertCurrency($this->getCommission()))
            ->setCommissionPlusFormated($this->_helper->convertCurrency($this->getCommissionPlus()))
            ->setCommissionPlus(floatval($this->getCommissionPlus()))
            ->setAccountName($account->getName())
            ->setAccountEmail($account->getEmail())
            ->setCreatedAtFormated($this->_helper->formatDate($transaction->getCreatedTime(), \IntlDateFormatter::MEDIUM))
            ->setTransactionId($transaction->getId())
        ;

        $template = $this->_helper->getConfig(self::XML_PATH_NEW_TRANSACTION_ACCOUNT_EMAIL, $store->getId());

        $sendTo = array(
            array(
                'email' => $account->getEmail(),
                'name'  => $account->getName(),
            )
        );

        $this->setLevel($this->getLevel()+1);
        $sender = $this->_helper->getSenderContact();
        $this->setTransEmailIdentSupport($this->_helper->getConfig('trans_email/ident_support/email'));

        foreach ($sendTo as $recipient) {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $store->getId()])
                ->setTemplateVars(
                    [
                        'transaction' => $this,
                        'store' => $store,
                        'trans_email_ident_support' => $this->_helper->getConfig('trans_email/ident_support/email'),
                        'sender_name' => $sender['name']
                    ]
                )
                ->setFrom($sender)
                ->addTo($recipient['email'], $recipient['name'])
                ->getTransport();
            $transport->sendMessage();
        }
        $this->setLevel($this->getLevel()-1);

        return $this;
    }

    /**
     * send email updated transaction to affiliate tier
     *
     * @return void
     */

    public function sendMailUpdatedTransactionToAccount($transaction, $isCompleted){
        if(!$this->_helperConfig->getEmailConfig('multilevel_is_sent_email_account_updated_transaction'))
            return $this;

        $store = $this->_storeManager->getStore($transaction->getStoreId());
        $currentCurrency = $store->getCurrentCurrency();
        $store->setCurrentCurrency($store->getBaseCurrency());

        $account = $this->_accountFactory->create()->load($this->getTierId());

        $this->setProducts($this->_helper->getFrontendProductHtmls($transaction->getOrderItemIds()))
            ->setTotalAmountFormated($this->_helper->convertCurrency($transaction->getTotalAmount()))
            ->setCommissionFormated($this->_helper->convertCurrency($this->getCommission()))
            ->setCommissionPlusFormated($this->_helper->convertCurrency($this->getCommissionPlus()))
            ->setCommissionPlus(floatval($this->getCommissionPlus()))
            ->setAccountName($account->getName())
            ->setAccountEmail($account->getEmail())
            ->setCreatedAtFormated($this->_helper->formatDate($transaction->getCreatedTime(), \IntlDateFormatter::MEDIUM))
            ->setIsCompleted($isCompleted)
            ->setTransactionId($transaction->getId())
        ;

        $template = $this->_helper->getConfig(self::XML_PATH_UPDATED_TRANSACTION_ACCOUNT_EMAIL, $store->getId());

        $sendTo = array(
            array(
                'email' => $account->getEmail(),
                'name'  => $account->getName(),
            )
        );

        $this->setLevel($this->getLevel()+1);
        $sender = $this->_helper->getSenderContact();
        foreach ($sendTo as $recipient) {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $store->getId()])
                ->setTemplateVars(
                    [
                        'transaction' => $this,
                        'store' => $store,
                        'trans_email_ident_support' => $this->_helper->getConfig('trans_email/ident_support/email'),
                        'sender_name' => $sender['name']
                    ]
                )
                ->setFrom($sender)
                ->addTo($recipient['email'], $recipient['name'])
                ->getTransport();
            $transport->sendMessage();

        }
        $this->setLevel($this->getLevel()-1);

        return $this;
    }

    /**
     * send email updated transaction to affiliate tier
     *
     * @return void
     */
    public function sendMailReducedTransactionToAccount($transaction, $reduceCommission, $totalReduce) {
        if(!$this->_helperConfig->getEmailConfig('multilevel_is_sent_email_account_updated_transaction'))
            return $this;

        $store = $this->_storeManager->getStore($transaction->getStoreId());
        $currentCurrency = $store->getCurrentCurrency();
        $store->setCurrentCurrency($store->getBaseCurrency());

        $account = $this->_accountFactory->create()->load($this->getTierId());

        $this->setProducts($this->_helper->getFrontendProductHtmls($transaction->getOrderItemIds()))
            ->setTotalAmountFormated($this->_helper->convertCurrency($transaction->getTotalAmount()))
            ->setCommissionFormated($this->_helper->convertCurrency($this->getCommission()))
            ->setCommissionPlusFormated($this->_helper->convertCurrency($this->getCommissionPlus()))
            ->setCommissionPlus(floatval($this->getCommissionPlus()))
            ->setAccountName($account->getName())
            ->setAccountEmail($account->getEmail())
            ->setCreatedAtFormated($this->_helper->formatDate($transaction->getCreatedTime(),\IntlDateFormatter::MEDIUM))
            ->setReducedCommission($this->_helper->convertCurrency($reduceCommission))
            ->setTotalReduced($this->_helper->convertCurrency($totalReduce))
            ->setTransactionId($transaction->getId());

        $template = $this->_helper->getConfig(self::XML_PATH_REDUCED_TRANSACTION_ACCOUNT_EMAIL, $store->getId());

        $sendTo = array(
            array(
                'email' => $account->getEmail(),
                'name'  => $account->getName(),
            )
        );

        $this->setLevel($this->getLevel()+1);
        $sender = $this->_helper->getSenderContact();
        foreach ($sendTo as $recipient) {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $store->getId()])
                ->setTemplateVars(
                    [
                        'transaction' => $this,
                        'store' => $store,
                        'trans_email_ident_support' => $this->_helper->getConfig('trans_email/ident_support/email'),
                        'sender_name' => $sender['name']
                    ]
                )
                ->setFrom($sender)
                ->addTo($recipient['email'], $recipient['name'])
                ->getTransport();
            $transport->sendMessage();
        }
        $this->setLevel($this->getLevel()-1);

        return $this;
    }

}