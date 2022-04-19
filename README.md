# Set VAT ID requirement dependency based on country

This module allows you to:
* Set VAT ID requirement dependency based on country
* Select the countries for which VAT ID is required
* Hide/Show VAT ID field on checkout depending on the country requirement

## Installation

### Composer
```
    composer require catgento/magento2-vatid-country-dependency
```

## Configuration

In Stores > Configuration > Customers > Customer Configuration > Name and Address Options > Show Tax/VAT Number, when you select 'Required', two new fields will be displayed:
* Required Countries for VAT (empty for all): you can select here the countries for which VAT ID is required (if no country is selected, VAT ID will be required for all, keeping default Magento functionality)
* Hide VAT field if not required: you can hide VAT field if VAT ID is not required for the selected countries
