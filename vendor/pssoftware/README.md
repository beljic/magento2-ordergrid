# PSSoftware OrderGrid extension

## Tested on Version

* Magento Commerce 2.4 
* Magento Open Source 2.4
* Magento Open Source 2.3

## Main Functionalities
* Add columns to Sales Order Grid and Sales Order Archive Grid (UI component customization),
* Copy coupon_code and discount_amount values from sales_order to sales_order_grid and magento_sales_order_grid_archive tables during installation,
* Copy coupon_code and discount_amount values from sales_order to sales_order_grid table after new order is placed.

## Installation 

#### Composer
Use the following commands to install this module into Magento 2:

    composer require beljic/ordergrid
    bin/magento module:enable PSSoftware_OrderGrid
    bin/magento setup:upgrade
       
#### Manual 
These are the steps:
* Upload the files into folder `app/code` of your site
* Run `php -f bin/magento module:enable PSSoftware_OrderGrid`
* Run `php -f bin/magento setup:upgrade`
* Flush the Magento cache `php -f bin/magento cache:flush`
* Done

## Instruction to uninstall
    bin/magento module:uninstall --non-composer PSSoftware_OrderGrid
