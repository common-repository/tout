<?php
/*
Plugin Name: Tout Wordpress Plugin
Plugin URI: http://wordpress.org/extend/plugins/tout/
Description: Enables the Tout SDK on all pages.
Version: 1.1.1
Author: Javid Jamae
Author URI: http://www.tout.com/
*/

/*
    Tout Wordpress Plugin
    Copyright (C) 2015 Javid Jamae

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

function activate_tout_wordpress_plugin() {
  add_option('tout_property_id', '------');
  add_option('tout_top_article_match_code', '// Custom Javascript that should run when a top-article match exists');
  add_option('tout_mid_article_match_code', '// Custom Javascript that should run when a mid-article match exists');
}

function admin_init_tout_wordpress_plugin() {
  register_setting('tout_wordpress_plugin', 'tout_property_id');
  register_setting('tout_wordpress_plugin', 'tout_top_article_match_code');
  register_setting('tout_wordpress_plugin', 'tout_mid_article_match_code');
}

function admin_menu_tout_wordpress_plugin() {
  add_options_page('Tout', 'Tout', 'manage_options', 'tout_wordpress_plugin', 'options_page_tout_wordpress_plugin');
}

function options_page_tout_wordpress_plugin() {
  include(WP_PLUGIN_DIR.'/tout/options.php');  
}

// Add Tout sdk.js to wp_head()
function tout_wordpress_plugin() {
  $tout_property_id = get_option('tout_property_id');
  $tout_top_article_match_code = get_option('tout_top_article_match_code');
  $tout_mid_article_match_code = get_option('tout_mid_article_match_code');
  $author_id = get_post_field( 'post_author', $post_id);
?>
  <meta property="tout:article:id" content="<?php echo md5(get_the_ID()) ?>"/>
  <meta property="tout:article:author" content="<?php echo the_author_meta('first_name', $author_id) ?> <?php echo the_author_meta('last_name', $author_id) ?>"/>
  <meta property="tout:article:pubdate" content="<?php echo get_the_time('c') ?>"/>

  <script class="tout-sdk tout-sdk-script-tag">
  !function(){var TOUT=window.TOUT=window.TOUT||{},utils={getCanonicalLinkHref:function(){for(var links=document.getElementsByTagName("link"),i=0;i<links.length;i++)if("canonical"===links[i].getAttribute("rel"))return links[i].getAttribute("href");return""},getMetaTagContentByProperty:function(metaTagProperty){for(var metaTags=document.getElementsByTagName("meta"),i=0;i<metaTags.length;i++)if(metaTags[i].getAttribute("property")===metaTagProperty)return metaTags[i].getAttribute("content");return""},getWindowLocationOrigin:function(){return window.location.protocol+"//"+window.location.host},getCanonicalUrl:function(){var canonicalUrl=utils.getCanonicalLinkHref()||utils.getMetaTagContentByProperty("og:url");return canonicalUrl&&"/"===canonicalUrl[0]&&(canonicalUrl=utils.getWindowLocationOrigin()+canonicalUrl),canonicalUrl},getOgUrl:function(){var ogUrl=utils.getMetaTagContentByProperty("og:url");return ogUrl&&"/"===ogUrl[0]&&(ogUrl=utils.getWindowLocationOrigin()+ogUrl),ogUrl},getRelCanonical:function(){var relCanonical=utils.getCanonicalLinkHref();return relCanonical&&"/"===relCanonical[0]&&(relCanonical=utils.getWindowLocationOrigin()+relCanonical),relCanonical},getExternalArticleId:function(){return utils.getMetaTagContentByProperty("tout:article:id")},getCurrentProtocol:function(){return"https:"===document.location.protocol?"https:":"http:"},getPlatformHost:function(){return TOUT.SDK_HOST||"platform.tout.com"}};TOUT.mapAsyncFetchApp={init:function(brandUid,options){this.brandUid=brandUid,this.active=!0,this.productFetched=!1,this.dataLoaded=!1,this.startedSuccessfully=!1,this.options=options||{},this.options.paramsWhitelist||(this.options.paramsWhitelist=["brand_uid","external_article_id","og_url","window_location","rel_canonical","async_fetch"])},fetch:function(){var script=document.createElement("script"),src=utils.getCurrentProtocol()+"//"+utils.getPlatformHost()+"/mid_article_player.js",params=TOUT.mapAsyncFetchApp.getMidArticleQueryParams(),joinCharacter="?";for(var param in params)params.hasOwnProperty(param)&&""!==params[param]&&(src+=joinCharacter+param+"="+encodeURIComponent(params[param]),joinCharacter="&");script.src=src;var firstScript=document.getElementsByTagName("script")[0];firstScript.parentNode.insertBefore(script,firstScript)},start:function(){this.productFetched&&this.dataLoaded&&!this.startedSuccessfully&&(this.startedSuccessfully=!0,TOUT.midArticleProductLoader.start(TOUT.data.mid_article_player_experiment))},getMidArticleQueryParams:function(){var params={};return this._whitelistContains("brand_uid")&&(params.brand_uid=this.brandUid),this._whitelistContains("content_referrer")&&(params.content_referrer=document.referrer),this._whitelistContains("external_article_id")&&(params.external_article_id=utils.getExternalArticleId()),this._whitelistContains("og_url")&&(params.og_url=utils.getOgUrl()),this._whitelistContains("window_location")&&(params.window_location=document.location.href),this._whitelistContains("rel_canonical")&&(params.rel_canonical=utils.getRelCanonical()),this._whitelistContains("async_fetch")&&(params.async_fetch=!0),params},_whitelistContains:function(value){return this.options.paramsWhitelist&&this.options.paramsWhitelist.indexOf(value)>-1}}}();
  !function(){var TOUT=window.TOUT=window.TOUT||{};if(console&&console.log&&console.log("Tout SDK: "+ +new Date),!TOUT._sdkScriptTagParsedAt){TOUT._sdkScriptTagParsedAt=new Date,TOUT.EMBED_CODE_VERSION="1.2.0";var sdkHost=TOUT.SDK_HOST||"platform.tout.com",sdkProtocol=TOUT.SDK_PROTOCOL||("https:"==window.location.protocol?"https:":"http:"),analyticsHost=TOUT.SDK_ANALYTICS_HOST||"analytics.tout.com",analyticsProtocol=TOUT.SDK_ANALYTICS_PROTOCOL||sdkProtocol;TOUT.onReady=TOUT.onReady||function(func){return TOUT._onReadyQueue=TOUT._onReadyQueue||[],TOUT._onReadyQueue.push(func),TOUT},TOUT.fireSimpleAnalyticsPixel=function(trigger_name,attrs){var img=new Image,url=analyticsProtocol+"//"+analyticsHost+"/events?trigger="+trigger_name;for(var attr in attrs)attrs.hasOwnProperty(attr)&&(url+="&"+attr+"="+encodeURIComponent(attrs[attr]));return img.src=url,img},TOUT.init=function(brandUid,options){options=options||{};var sdkScriptId="tout-js-sdk";if(document.getElementById(sdkScriptId)&&!options.forceInit)return TOUT;if(brandUid=TOUT.SDK_BRAND_UID||brandUid,"undefined"==typeof brandUid||"string"!=typeof brandUid||0===brandUid.length||brandUid.length>7)return TOUT.fireSimpleAnalyticsPixel("sdk_log",{log_level:"error",log_message:"BRAND_UID_NOT_DEFINED",content_page_url:window.location.href}),console&&console.error&&console.error("TOUT - Invalid Brand UID: "+brandUid),TOUT;TOUT._initOptions=options;var script=document.createElement("script");script.type="text/javascript",script.src=sdkProtocol+"//"+sdkHost+"/sdk/v1/"+brandUid+".js",script.id=sdkScriptId,script.className="tout-sdk";var firstScript=document.getElementsByTagName("script")[0];return firstScript.parentNode.insertBefore(script,firstScript),TOUT.fireSimpleAnalyticsPixel("sdk_initialized",{content_brand_uid:brandUid,sdk_embed_code_version:TOUT.EMBED_CODE_VERSION,content_page_url:window.location.href}),TOUT}}}();
  (function(){
    var brandUid = '<?php echo $tout_property_id ?>';
    TOUT.topArticleLoaded = function(attrs) {
      if(attrs.matchExists){
        <?php echo $tout_top_article_match_code . "\n" ?>
      }
    };
    TOUT.midArticleLoaded = function(attrs) {
      if(attrs.matchExists){
        <?php echo $tout_mid_article_match_code . "\n" ?>
      }
    };
    TOUT.mapAsyncFetchApp.init(brandUid);
    TOUT.init(brandUid);
    TOUT.mapAsyncFetchApp.fetch();
  })();
  </script>
<?php
}

register_activation_hook(__FILE__, 'activate_tout_wordpress_plugin');

if (is_admin()) {
  add_action('admin_init', 'admin_init_tout_wordpress_plugin');
  add_action('admin_menu', 'admin_menu_tout_wordpress_plugin');
}

if (!is_admin()) {
  add_action('wp_head', 'tout_wordpress_plugin' );
}

?>
