<?php

namespace Catgento\VatIdCountryDependency\Block\Checkout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class VatIdProcessor
{
    const CONFIG_PATH_REQUIRED_COUNTRIES = 'customer/address/vat_required_countries';
    const CONFIF_PATH_HIDE_VAT_FIELD = 'customer/address/hide_vat_field_not_required';
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Checkout LayoutProcessor after process plugin.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processor, $jsLayout)
    {
        $shippingConfiguration = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        //Checks if shipping step available.
        if (isset($shippingConfiguration)) {
            $shippingConfiguration = $this->processAddress(
                $shippingConfiguration,
                'shippingAddress',
                [
                    'checkoutProvider',
                    'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id'
                ]
            );
        }

        return $jsLayout;
    }

    /**
     * Process provided address to contains checkbox and have trackable address fields.
     *
     * @param $addressFieldset - Address fieldset config.
     * @param $dataScope - data scope
     * @param $deps - list of dependencies
     * @return array
     */
    private function processAddress($addressFieldset, $dataScope, $deps)
    {
        $addressFieldset['vat_id'] = [
            'component' => 'Catgento_VatIdCountryDependency/js/vatid-dependency',
            'label' => __('VAT number'),
            'config' => [
                'customScope' => $dataScope,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'requiredCountries' => $this->getConfigValue(self::CONFIG_PATH_REQUIRED_COUNTRIES),
                'hideVatFieldIfNotRequired' => $this->getConfigValue(self::CONFIF_PATH_HIDE_VAT_FIELD),
                'tooltip' => [
                    'description' => __('Enter your VAT number if you have one.'),
                    'short' => __('VAT number'),
                ],
            ],
            'deps' => $deps,
            'provider' => 'checkoutProvider',
            'dataScope' => 'shippingAddress.vat_id',
            'sortOrder' => 140,
            'validation' => [
                'required-entry' => true
            ]
        ];

        return $addressFieldset;
    }

    /**
     * Returns whatsapp number
     * @param $field
     * @return mixed
     */
    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getId()
        );
    }
}
