<?php
/**
 * Annuaire_artisans Uninstall
 *
 * Uninstalling Annuaire_artisans deletes all options.
 *
 * @package annuaire_artisans
 * @since 1.0.0
 */

/** Check if we are uninstalling. */
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/** Delete options. */
delete_option('tuxbfu_max_upload_size');
delete_option('tuxbfu_chunk_size');
delete_option('tuxbfu_max_retries');
