ALTER TABLE `categories` 
  DROP INDEX `categories_shortcode_unique`;

ALTER TABLE `categories`
  ADD UNIQUE KEY `categories_api_id_shortcode_unique` (`api_id`,`shortcode`);

