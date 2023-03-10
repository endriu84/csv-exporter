#!/bin/bash

echo "Initializing"

wp_env_path=$(pnpm wp-env install-path)
docker_compose_file="${wp_env_path}/docker-compose.yml"

sed -i "s/user: '33:33'/user: '1000:1000'/g" "${docker_compose_file}"

sed -i "s:wordpress_wp_env_path:${wp_env_path}/WordPress:g" workspace.code-workspace

wp_cli="pnpm wp-env run cli"

${wp_cli} "plugin activate woocommerce"

# wp user create customer customer@woocommercecoree2etestsuite.com --user_pass=password --role=subscriber --path=/var/www/html

# we cannot create API keys for the API, so we using basic auth, this plugin allows that.
# wp plugin install https://github.com/WP-API/Basic-Auth/archive/master.zip --activate

# update permalinks to `pretty` to make it easier for testing APIs with k6
# ${wp_cli} "option update permalink_structure '/%postname%'"
# ${wp_cli} "rewrite flush --hard"

# install the WP Mail Logging plugin to test emails
# ${wp_cli} "plugin install wp-mail-logging --activate"

# Installing and activating the WordPress Importer plugin to import sample products"
${wp_cli} "plugin install wordpress-importer --activate"

# Adding basic WooCommerce settings"
${wp_cli} "option set woocommerce_store_address 'Example Address Line 1'"
${wp_cli} "option set woocommerce_store_address_2 'Example Address Line 2'"
${wp_cli} "option set woocommerce_store_city 'Example City'"
${wp_cli} "option set woocommerce_default_country 'US:CA'"
${wp_cli} "option set woocommerce_store_postcode '94110'"
${wp_cli} "option set woocommerce_currency 'USD'"
${wp_cli} "option set woocommerce_product_type 'both'"
${wp_cli} "option set woocommerce_allow_tracking 'no'"
${wp_cli} "option set woocommerce_enable_checkout_login_reminder 'yes'"
${wp_cli} "option set --format=json woocommerce_cod_settings '{\"enabled\":\"yes\"}'"

#  WooCommerce shop pages
${wp_cli} "wc --user=admin tool run install_pages"

# Importing WooCommerce sample products"
${wp_cli} "import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=skip"

echo "Success!"
