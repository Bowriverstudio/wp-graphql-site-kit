=== WPGraphQL ===
Contributors: Maurice
Tags: GraphQL, Google Site Kit, Headless Wordpress,
Requires PHP: 7.1
Stable tag: 1.6.11
License: GPL-3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

=== Description ===

WPGraphQL Site Kit is a free, open-source WordPress plugin that extends the GraphQL schema to include Google Anyalitics Code.


=== Dependencies ===
WP GraphQL
Site Kit by Google
Site Kit Dev Settings
https://sitekit.withgoogle.com/service/download/google-site-kit-dev-settings.zip

=== Site Kit by Google Setup ===

Please Read this when setting up Site kit.
https://github.com/google/site-kit-wp/issues/37#issuecomment-815074629
https://sitekit.withgoogle.com/documentation/using-site-kit-on-a-staging-environment/

=== GraphQL ===

```graphql
query MyQuery {
  googleSiteKit {
    analytics {
      propertyID
    }
    analytics4 {
      propertyID
    }
  }
}
```