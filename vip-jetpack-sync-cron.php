<?php

/*
Plugin Name: VIP Jetpack Sync Cron
Description: This plugin ensures that Jetpack only syncs on the dedicated cron task.
Version: 2.0
Author: Rebecca Hum, Automattic
*/

use Automattic\Jetpack\Sync\Actions;
use Automattic\Jetpack\Sync\Settings;

class VIP_Jetpack_Sync_Cron {

	const SYNC_INTERVAL_NAME = 'vip_jp_sync_cron_interval';

	/**
	 * Add the necessary hooks.
	 */
	public static function init() {
		if ( ! class_exists( 'Jetpack' ) ) { // Bail if no Jetpack.
			return;
		}

		if ( ! Actions::sync_via_cron_allowed() ) { // Bail if no syncing via cron allowed.
			return;
		}

		add_filter( 'cron_schedules', [ __CLASS__, 'jp_sync_cron_schedule_interval' ] );
		add_filter( 'jetpack_sync_incremental_sync_interval', [ __CLASS__, 'filter_jetpack_sync_interval' ], 999 );
		add_filter( 'jetpack_sync_full_sync_interval', [ __CLASS__, 'filter_jetpack_sync_interval' ], 999 );
		add_filter( 'jetpack_sync_sender_should_load', [ Settings::class, 'is_doing_cron' ], 999 ); // Short circuit loading of Jetpack sender to sync only on cron.
	}

	/**
	 * Filter to add custom interval to schedule.
	 *
	 * @param array $schedules
	 * @return array $schedules
	 */
	public static function jp_sync_cron_schedule_interval( $schedules ) {
		$interval = apply_filters(
			'vip_jetpack_sync_cron_interval',
			[
				'interval' => 60,
				'display'  => 'Every minute',
			]
		);

		$schedules[ self::SYNC_INTERVAL_NAME ] = $interval;

		return $schedules;
	}

	/**
	 * Filter to return custom cron interval name.
	 *
	 * @return string The cron schedule / interval's name.
	 */
	public static function filter_jetpack_sync_interval() {
		return self::SYNC_INTERVAL_NAME;
	}
}

add_action( 'after_setup_theme', [ 'VIP_Jetpack_Sync_Cron', 'init' ] ); // Since Jetpack autoloads their classes, we need to hook later.