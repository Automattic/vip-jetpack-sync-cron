# VIP Jetpack Sync Cron
By default, Jetpack Sync will sometimes attempt to piggyback on various cron or API requests - causing slowdowns on the shutdown hook. This plugin modifies that behaviour and ensures that Jetpack only syncs on the dedicated cron task.

## Installation

### Option One: Normal Plugin

Add the `vip-jetpack-sync-cron` plugin folder in your `/plugins` directory. Then load the plugin either in your theme or in the `plugin-loader.php` client mu plugin with a call like this:

```
wpcom_vip_load_plugin( 'vip-jetpack-sync-cron' );
```

### Option Two: MU Plugin

Add the `vip-jetpack-sync-cron` plugin folder to your `/client-mu-plugins` directory. Then load the plugin in the `plugin-loader.php` file with a call like this:

```
require_once WPCOM_VIP_CLIENT_MU_PLUGIN_DIR . '/vip-jetpack-sync-cron/vip-jetpack-sync-cron.php';
```
