Импорт товаров из партнерок
===========================

Admitad
=======

***get list companies***

http://export.admitad.com/ru/webmaster/websites/40785/partners/export/?user=Antonlukk&code=7569a359ca&format=xml&filter=1&keyword=&region=00&action_type=&status=active&format=xml

***path to url price list***

    advcampaign > original_products
    
    advcampaign:
        name
        id
        original_products

***get one price list for company***

http://export.admitad.com/ru/webmaster/websites/40785/products/export_adv_products/?feed_id=14236&code=7569a359ca&user=Antonlukk&format=xml

***element tag***

    offer
    
***field list ***
    
    select name, count(name) from key_product group by name;
    +-----------------------+-------------+
    | name                  | count(name) |
    +-----------------------+-------------+
    |                       |        2894 |
    | @available            |     7780822 |
    | @balance              |        1136 |
    | @bid                  |      353253 |
    | @cbid                 |       93291 |
    | @group_id             |     3252268 |
    | @groupId              |        1972 |
    | @id                   |     8884669 |
    | @selling_type         |       55911 |
    | @type                 |     6238779 |
    | 1326.xml              |         164 |
    | 13803.xml             |           1 |
    | 5""                   |          43 |
    | 6""                   |           1 |
    | active                |       15847 |
    | actualProfit          |       57983 |
    | additional_imageurl   |        2683 |
    | adult                 |        5100 |
    | age                   |      150043 |
    | anonsPic              |         240 |
    | article               |        5874 |
    | author                |      443335 |
    | availability          |         554 |
    | available             |        6076 |
    | barcode               |       85605 |
    | baseprice             |         656 |
    | binding               |      200188 |
    | brand                 |       51597 |
    | campaign_end_date     |        6076 |
    | campaign_start_date   |        6076 |
    | category_path         |      144917 |
    | categoryId            |     8884669 |
    | code                  |       68133 |
    | color                 |        6076 |
    | colour                |       25432 |
    | condition             |       25432 |
    | country               |           3 |
    | country_of_origin     |      281025 |
    | cpa                   |      882445 |
    | currencyId            |     8878593 |
    | date_expiry           |       28598 |
    | delivery              |     5317369 |
    | desc                  |          35 |
    | description           |     5891493 |
    | dimensions            |       12094 |
    | director              |        2119 |
    | discount              |      398156 |
    | downloadable          |      306332 |
    | expectedPercent       |       57983 |
    | expectedProfit        |       57983 |
    | format                |        6572 |
    | gender                |       33854 |
    | goodsNumber           |      155136 |
    | goodsStatus           |      155136 |
    | gtin                  |       25432 |
    | guarantee             |       68203 |
    | guid                  |       40393 |
    | id                    |       29122 |
    | image                 |      538499 |
    | ISBN                  |      420953 |
    | kidsgender            |         890 |
    | language              |       40393 |
    | local_delivery_cost   |     1661429 |
    | manufacturer_warranty |     1998044 |
    | margin                |        9375 |
    | market_category       |     1527220 |
    | material              |        6076 |
    | maxProfit             |       57983 |
    | media                 |        2121 |
    | model                 |     5093656 |
    | modified_time         |     8884662 |
    | mpn                   |       25432 |
    | name                  |     8884662 |
    | old_price             |       22590 |
    | oldprice              |     2072639 |
    | oriCountry            |      155136 |
    | originalName          |        1171 |
    | page_extent           |      332941 |
    | param                 |     6163683 |
    | part                  |         171 |
    | performed_by          |        6229 |
    | pickup                |     4077987 |
    | picture               |     8203273 |
    | postCountry           |      155136 |
    | prev_price            |       11816 |
    | price                 |     8884654 |
    | price_old             |       31576 |
    | price_with_delivery   |      132180 |
    | product_type          |       13865 |
    | publisher             |      476739 |
    | quantity              |       27597 |
    | real_category_name    |      398088 |
    | rec                   |        1907 |
    | sales_notes           |     3387723 |
    | seller_warranty       |        2374 |
    | series                |      318290 |
    | shipping              |       13865 |
    | short_text            |       15808 |
    | size                  |       31508 |
    | SKU                   |         549 |
    | starring              |        2012 |
    | stock                 |       82358 |
    | store                 |     2303245 |
    | title                 |      567726 |
    | topseller             |      846212 |
    | transportFee          |      155136 |
    | transportTime         |      155136 |
    | trend                 |       57983 |
    | typePrefix            |     3926628 |
    | uri                   |       57983 |
    | url                   |     8884670 |
    | vendor                |     7142083 |
    | vendorCode            |     4362714 |
    | video                 |         294 |
    | volume                |          89 |
    | weight                |       50715 |
    | wprice                |       11792 |
    | year                  |      441294 |
    | youtube               |        2580 |
    +-----------------------+-------------+
    
Actionpay
=========

***Get list companies***

https://api.actionpay.ru/ru/apiWmMyOffers/?key=E1RBQymTBLV53g92yjZc&format=xml

***path to url price list***

    favouriteOffer > offer > id
    
    

***Get offers from company***

    https://api.actionpay.ru/ru/apiWmOffers/?key=E1RBQymTBLV53g92yjZc&format=xml&offer={id}

https://api.actionpay.ru/ru/apiWmOffers/?key=E1RBQymTBLV53g92yjZc&format=xml&offer=6253

***Get price list from offers***
    
    offer > Ymls > Yml
    
По этому url получим xml в формате yandex market lang.

***field list ***

    select name, count(name) from key_product_ap group by name;
    +-----------------------+-------------+
    | name                  | count(name) |
    +-----------------------+-------------+
    |                       |          68 |
    | @available            |     6062646 |
    | @bid                  |       27074 |
    | @cbid                 |        2640 |
    | @group_id             |     3783233 |
    | @id                   |     6062646 |
    | @type                 |     5485764 |
    | add_params            |     1090644 |
    | age                   |     2327105 |
    | amount                |       38251 |
    | artist                |       68892 |
    | author                |     2910738 |
    | barcode               |     3402356 |
    | baseprice             |     4059829 |
    | binding               |     1391973 |
    | category_path         |      144917 |
    | categoryId            |     6062588 |
    | characteristics       |         418 |
    | color                 |        1045 |
    | content               |       53092 |
    | country               |       33346 |
    | currencyId            |     6062645 |
    | delivery              |     6005668 |
    | description           |     5722798 |
    | director              |       30224 |
    | downloadable          |         950 |
    | ISBN                  |     2942514 |
    | jewel                 |       38251 |
    | language              |     2877399 |
    | local_delivery_cost   |       78625 |
    | manufacturer_warranty |      682687 |
    | margin                |       38184 |
    | market_category       |         996 |
    | media                 |      107320 |
    | model                 |     1990443 |
    | modelId               |       38251 |
    | name                  |     4169503 |
    | old_price             |       13622 |
    | oldprice              |      268482 |
    | orderingTime          |     4059831 |
    | originalName          |       23266 |
    | page_extent           |     3182877 |
    | param                 |     1681696 |
    | performed_by          |         720 |
    | pickup                |     1299366 |
    | picture               |     5671121 |
    | price                 |     6062645 |
    | publisher             |     2887404 |
    | releaseyear           |       33313 |
    | sales_notes           |     1131533 |
    | series                |      661112 |
    | size                  |        1495 |
    | SKU                   |       12579 |
    | starring              |       30331 |
    | store                 |       66387 |
    | table_of_contents     |      161239 |
    | title                 |      112714 |
    | type                  |          82 |
    | typePrefix            |     1764181 |
    | url                   |     6062645 |
    | vendor                |     2564819 |
    | vendorCode            |     2054650 |
    | wprice                |        8340 |
    | year                  |     3433134 |
    | yml727.xml            |           2 |
    | yml755.xml            |           2 |
    +-----------------------+-------------+
