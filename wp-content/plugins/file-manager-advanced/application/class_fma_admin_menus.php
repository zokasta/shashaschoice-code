<?php
/*
@package: File Manager Advanced
@Class: fma_admin_menus
*/
if(class_exists('class_fma_admin_menus')) {
	return;
}
class class_fma_admin_menus {
	var $langs;
	/**
	 * AFM - Languages
	 */
	 public function __construct() {
             include('class_fma_lang.php');
			$this->langs = new class_fma_adv_lang();

            add_action( 'fma__settings_tab_notifications_content', array( $this, 'notification_callback' ) );
	  }

    /**
     * Notification Callback
     * @since 6.7.3
     */
      public function notification_callback() {
          if ( ! class_exists( 'AFMP\\Modules\\EmailNotification\\EmailNotification' ) ) {
              echo '<div afmp-href="https://advancedfilemanager.com/pricing/?utm_source=plugin&utm_medium=email_notification&utm_campaign=plugin" class="fma__wrap">';
              echo '<h2 class="fma__heading">Email notification Settings <span class="fma__heading-pro-tag">PRO</span></h2>';

              echo '<table class="form-table" role="presentation"><tbody><tr><th scope="row"><label for="enable">Enable</label></th><td>            <input type="checkbox" id="enable" name="afmp__email_notification_settings[enable]" value="yes">
            <label for="enable">Enable email notification</label>
            </td></tr><tr><th scope="row"><label for="email">Email Address</label></th><td>            <input type="text" id="email" name="afmp__email_notification_settings[email]" value="" class="regular-text">
            <p class="description">Email address to send notification.</p>
            </td></tr><tr><th scope="row"><label for="events">Events</label></th><td>                <label>
                    <input type="checkbox" name="afmp__email_notification_settings[events][]" value="rm">
                    Delete File                </label><br>
                                <label>
                    <input type="checkbox" name="afmp__email_notification_settings[events][]" value="mkfile">
                    Create File                </label><br>
                                <label>
                    <input type="checkbox" name="afmp__email_notification_settings[events][]" value="mkdir">
                    Create Folder                </label><br>
                </td></tr><tr><th scope="row"><label for="subject">Email Subject</label></th><td>            <input type="text" id="subject" name="afmp__email_notification_settings[subject]" class="regular-text">
            <p class="description">Subject of the email notification.</p>
            </td></tr><tr><th scope="row"><label for="message">Email Message</label></th><td>            <textarea id="message" name="afmp__email_notification_settings[message]" rows="5" class="large-text">The file action {event} was performed on {file_name} ({ext}) at {date_time} from IP {ip_address} on {site_name} by {username}.
Thank you for using Advanced File Manager.</textarea>
            <p class="description">Message body of the email notification. You can use placeholders like {file_name}, {action} etc.</p>
            <p class="description">Available placeholders:</p><ul class="afmp-email-placeholders"><li><code>{username}</code> - This will fetch the user’s name who did the action</li><li><code>{ip_address}</code> - This will fetch the user’s IP Address</li><li><code>{event}</code> - This will fetch which file the user has created or deleted</li><li><code>{file_name}</code> - This will fetch the file name on which that action was done</li><li><code>{ext}</code> - This will fetch the file extension on which that action was done</li><li><code>{date_time}</code> - This will fetch the date the user acted on the file</li><li><code>{site_name}</code> - This will fetch the site name on which the action was performed</li></ul></td></tr></tbody></table>';
              echo '</div>';
          }

          if ( ! class_exists( 'AFMP\\Modules\\SlackNotification\\SlackNotification' ) ) {
              echo '<div afmp-href="https://advancedfilemanager.com/pricing/?utm_source=plugin&utm_medium=slack_notification&utm_campaign=plugin" class="fma__wrap">';
              echo '<h2 class="fma__heading">Slack Notification <span class="fma__heading-pro-tag">PRO</span></h2>';

              echo '<table class="form-table" role="presentation"><tbody><tr><th scope="row"><label for="afmp_slack_enable">Enable Slack Notification</label></th><td><input type="checkbox" id="afmp_slack_enable" name="afmp__slack_notification_settings[enable]" value="yes"><label for="afmp_slack_enable">Enable Slack Notification</label></td></tr><tr><th scope="row"><label for="afmp_slack_webhook_url">Slack Webhook URL</label></th><td><input type="text" id="afmp_slack_webhook_url" name="afmp__slack_notification_settings[webhook_url]" class="large-text"><p class="description">Enter your Slack Webhook URL to receive notifications.</p><a href="#">Click here to get your webhook URL</a></td></tr><tr><th scope="row"><label for="afmp_slack_event_notification">Event Notification</label></th><td><label><input type="checkbox" name="afmp__slack_notification_settings[events][]" value="rm">Delete File</label><br><label><input type="checkbox" name="afmp__slack_notification_settings[events][]" value="mkfile">Create File</label><br><label><input type="checkbox" name="afmp__slack_notification_settings[events][]" value="mkdir">Create Folder</label><br></td></tr><tr><th scope="row"><label for="slack_notification_message">Slack Notification Message</label></th><td><textarea id="slack_notification_message" name="afmp__slack_notification_settings[message]" rows="5" class="large-text">A quick update from the Advance File Manager plugin on your site {site_name}.
A file was {event} by {username} on {date_time}. The file name is {file_name} with the extension {ext}, and the action was performed from the following IP Address: {ip_address}.</textarea><p class="description">Customize the message to be sent to Slack. You can use placeholders like {file_name}, {action}, etc.</p><p class="description">Available placeholders:</p><ul class="afmp-slack-placeholders"><li><code>{username}</code> - This will fetch the user’s name who did the action</li><li><code>{ip_address}</code> - This will fetch the user’s IP Address</li><li><code>{event}</code> - This will fetch which file the user has created or deleted</li><li><code>{file_name}</code> - This will fetch the file name on which that action was done</li><li><code>{ext}</code> - This will fetch the file extension on which that action was done</li><li><code>{date_time}</code> - This will fetch the date the user acted on the file</li><li><code>{site_name}</code> - This will fetch the site name on which the action was performed</li></ul></td></tr></tbody></table>';
              echo '</div>';
          }

      }

	/**
	 * Loading Menus
	 */
	public function load_menus() {
		
        $fmaPer = $this->fmaPer();

        /** Authorizing only super admin to manage settings */
        $subPer = 'manage_options';
        if ( is_multisite() && !is_network_admin() ) {
            $subPer = 'manage_network';
            $fmaPer = $this->networkPer();
        }

        add_menu_page(
            __( 'File Manager', 'file-manager-advanced' ),
            __( 'File Manager', 'file-manager-advanced' ),
            $fmaPer,
            'file_manager_advanced_ui',
            array($this, 'file_manager_advanced_ui'),
            plugins_url( 'assets/icon/fma.png', __FILE__ ),
            4
        );
        add_submenu_page( 'file_manager_advanced_ui', 'Settings', 'Settings', $subPer, 'file_manager_advanced_controls', array(&$this, 'file_manager_advanced_controls'));
        if(!class_exists('file_manager_advanced_shortcode')) {
		    add_submenu_page( 'file_manager_advanced_ui', 'Shortcodes', 'Shortcodes', $subPer, 'file_manager_advanced_shortcodes', array(&$this, 'file_manager_advanced_shortcodes'));
	    }

		if ( ! class_exists( 'AFMP\\Modules\\Adminer' ) ) {
			add_submenu_page( 'file_manager_advanced_ui', 'DB Access', 'DB Access', 'manage_options', 'afmp-adminer', array( $this, 'adminer_menu' ) );
		}

        if ( ! class_exists( 'AFMP\\Modules\\Dropbox' ) ) {
            add_submenu_page( 'file_manager_advanced_ui', 'Dropbox Settings', 'Dropbox', 'manage_options', 'afmp-dropbox', array( $this, 'dropbox_menu'  ) );
        }

        if ( ! class_exists( 'AFMP\\Modules\\FileLogs' ) ) {
            add_submenu_page( 'file_manager_advanced_ui', 'File Logs', 'File Logs', 'manage_options', 'afmp-file-logs', array( $this, 'afmp__file_logs' ), 2 );
        }
        
        if ( ! class_exists( 'AFMP\\Modules\\GoogleDrive' ) ) {
            add_submenu_page( 'file_manager_advanced_ui', 'Google Drive Settings', 'Google Drive', 'manage_options', 'afmp-googledrive', array( $this, 'googledrive_menu'  ) );
		}
		
        if ( ! class_exists( 'AFMP\Modules\Onedrive' ) ) {
            add_submenu_page( 'file_manager_advanced_ui', 'OneDrive Settings', 'OneDrive', 'manage_options', 'afmp-onedrive', array( $this, 'onedrive_menu'  ) );
        }
	}

	/**
	 * Dropbox menu
	 * @since 6.7.2
	 */
    public function dropbox_menu() {

        echo '
        <h2 class="dropbox__heading">Dropbox Settings <span class="dropbox__heading-pro-tag">PRO</span></h2>

        <div class="dropbox__wrap">
            <table class="form-table">
                <tr>
                    <th>
                        <lable for="fma__enable">Enable</lable>
                    </th>
                    <td>
                        <input type="checkbox" id="fma__enable">
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__alias">Alias</label>
                    </th>
                    <td>
                        <input type="text" id="afm__alias" class="regular-text">
                        <p class="desc">
                            <strong>Enter a title which will be displayed on File Manager</strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__app_key">App Key</label>
                    </th>
                    <td>
                        <input type="text" id="afm__app_key" class="regular-text">
                        <p class="desc">
                            <strong>Enter your Dropbox App key, you will get your app key from <a href="#">Dropbox App Console</a></strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__app_secret">App Secret</label>
                    </th>
                    <td>
                        <input type="text" id="afm__app_secret" class="regular-text">
                        <p class="desc">
                            <strong>Enter your Dropbox App secret, you will get your app secret from <a href="#">Dropbox App Console</a></strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__redirect_url">Redirect URL</label>
                    </th>
                    <td>
                        <input type="text" id="afm__redirect_uri" class="regular-text">
                        
                        <p class="desc">
                            <strong>
                                Copy this URL and paste it in your Dropbox App Console under Redirect URIs
                            </strong>
                        </p>
                    </td>
                </tr>
            </table>';

        submit_button();

        echo '</div>';
    }

    /**
     * OneDrive menu
     * @since 6.7.3
     */
    public function onedrive_menu() {

        echo '<style>
            .onedrive__heading {
                color: #000;
                font-size: 18px;
                font-style: normal;
                font-weight: 600;
                line-height: normal;
            }
            
            .onedrive__heading-pro-tag {
                display: inline-block;
                padding: 2px 8px;
                background: linear-gradient(270deg, #011D33 0%, #3F6972 100%);
                border-radius: 4px;
                color: #fff;
                font-size: 12px;
                margin-left: 25px;
            }
            
            .onedrive__wrap {
                opacity: 0.5;
                position:relative;
            }
            
            .onedrive__wrap::before {
                content: "";
                display: block;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                background: transparent;
            }
        </style>';

        echo '<h2 class="onedrive__heading">One Drive Settings <span class="onedrive__heading-pro-tag">PRO</span></h2>';
        echo '<div class="onedrive__wrap" afmp-href="">';
        echo '<h2></h2>';
        echo '<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="onedrive-enable">Enable</label>
						</th>
						<td>
							<input type="checkbox" name="afmp__onedrive_settings[enable]" id="onedrive-enable" value="yes">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="onedrive-alias">Alias</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="afmp__onedrive_settings[title]" id="onedrive-alias" value=""><p class="desc"><strong>Enter a title which will be displayed on File Manager</strong></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="onedrive-app-id">Application (client) ID</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="afmp__onedrive_settings[app_id]" id="onedrive-app-id" value="">
							<p class="desc">
								<strong>Enter your OneDrive Application (client) ID from your <a href="#">Azure AD app registration.</a></strong>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="onedrive-app-secret">Client secret</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="afmp__onedrive_settings[app_secret]" id="onedrive-app-secret" value="">
							<p class="desc">
								<strong>Enter your OneDrive Client secret from your <a href="#">Azure AD app registration.</a></strong>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="onedrive-redirect-uri">Redirect URI</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="afmp__onedrive_settings[redirect_uri]" id="onedrive-redirect-uri" value="">
							<p class="desc">
								<strong>Copy this URL and paste it in your Azure AD app registration under Redirect URIs.</strong>
							</p>
						</td>
					</tr>
				</tbody>
			</table>';

        submit_button();

        echo '</div>';
    }

	/**
	 * Google Drive menu
	 * @since 6.7.2
	 */
    public function googledrive_menu() {

        echo '<style type="text/css">
            .googledrive__heading {
                color: #000;
                font-size: 18px;
                font-style: normal;
                font-weight: 600;
                line-height: normal;
            }
            
            .googledrive__heading-pro-tag {
                display: inline-block;
                padding: 2px 8px;
                background: linear-gradient(270deg, #011D33 0%, #3F6972 100%);
                border-radius: 4px;
                color: #fff;
                font-size: 12px;
                margin-left: 25px;
            }
            
            .googledrive__wrap {
                opacity: 0.5;
                position:relative;
            }
            
            .googledrive__wrap::before {
                content: "";
                display: block;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                background: transparent;
            }
        </style>
        <h2 class="googledrive__heading">Google Drive Settings <span class="googledrive__heading-pro-tag">PRO</span></h2>

        <div class="googledrive__wrap">
            <table class="form-table">
                <tr>
                    <th>
                        <lable for="fma__enable">Enable</lable>
                    </th>
                    <td>
                        <input type="checkbox" id="fma__enable">
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__alias">Alias</label>
                    </th>
                    <td>
                        <input type="text" id="afm__alias" value="" class="regular-text">
                        <p class="desc">
                            <strong>Enter a title which will be displayed on File Manager</strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__app_key">App Key</label>
                    </th>
                    <td>
                        <input type="text" id="afm__app_key" class="regular-text">
                        <p class="desc">
                            <strong>Enter your Google Drive App key, you will get your app key from <a href="#">Google Drive App Console</a></strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__app_secret">App Secret</label>
                    </th>
                    <td>
                        <input type="text" id="afm__app_secret" class="regular-text">
                        <p class="desc">
                            <strong>Enter your Google Drive App secret, you will get your app secret from <a href="#">Google Drive App Console</a></strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__redirect_url">Javascript Origin</label>
                    </th>
                    <td>
                        <input type="text" id="afm__redirect_uri" class="regular-text">
                        
                        <p class="desc">
                            <strong>
                                Copy this URL and paste it in your Google Drive App Console under JavaScripts Origins
                            </strong>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="afm__redirect_url">Redirect URL</label>
                    </th>
                    <td>
                        <input type="text" id="afm__redirect_uri" class="regular-text">
                        
                        <p class="desc">
                            <strong>
                                Copy this URL and paste it in your Google Drive App Console under Redirect URIs
                            </strong>
                        </p>
                    </td>
                </tr>
            </table>';

        submit_button();

        echo '</div>';
    }

	/**
	 * Adminer menu
	 * @since 6.7.2
	 */
	public function adminer_menu() {
		require_once FMAFILEPATH . 'templates/adminer.php';
	}

    public function afmp__file_logs() {
        echo <<<HTML
<div class="wrap">

<h2 class="filelogs__heading">File Logs <span class="filelogs__heading-pro-tag">PRO</span></h2>


<div class="file-logs__wrap" afmp-href="https://advancedfilemanager.com/pricing/?utm_source=plugin&utm_medium=file_log_banner&utm_campaign=plugin">
<div class="afma-datatable-header"><a class="button button-secondary" href="#" style="float: right;">Delete All</a><form method="get">
					
					<input type="hidden" name="page" value="afmp-file-logs">
				    <input type="hidden" name="_wpnonce" value="449f66b7cf">
					
					<select name="filter" id="filter" class="afmp__select">
						<option value="all">All</option><option value="renamed">Renamed</option><option value="duplicated">Duplicated</option><option value="uploaded">Uploaded</option><option value="created">Created</option><option value="deleted">Deleted</option><option value="pasted">Pasted</option><option value="updated">Updated</option>
					</select>
					
					<input type="text" name="date-range" id="date-range" class="afmp__input" autocomplete="off" value="" placeholder="Date Range">
					
					<input type="submit" class="button button-secondary" value="Filter">
				</form></div><table class="wp-list-table widefat fixed striped table-view-list ">
			<thead>
	<tr>
		<th scope="col" id="id" class="manage-column column-id column-primary sortable asc"><a href="#"><span>ID</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="user_name" class="manage-column column-user_name sortable desc"><a href="https://file-manager-advanced.test/wp-admin/admin.php?page=afmp-file-logs&amp;orderby=user_id&amp;order=asc"><span>User</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort ascending.</span></a></th><th scope="col" id="time" class="manage-column column-time sortable asc"><a href="#"><span>Date &amp; Time</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="action" class="manage-column column-action sortable asc"><a href="#"><span>Event</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="file_path" class="manage-column column-file_path sortable asc"><a href="#"><span>File Path</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="type" class="manage-column column-type sortable asc"><a href="#"><span>Type</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="ip" class="manage-column column-ip sortable asc"><a href="#"><span>IP Address</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" id="actions" class="manage-column column-actions">Actions</th>	</tr>
	</thead>

	<tbody id="the-list">
		<tr><td class="id column-id has-row-actions column-primary" data-colname="ID">6<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:40 pm</td><td class="action column-action" data-colname="Event">uploaded</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\HeidiSQL.lnk</td><td class="type column-type" data-colname="Type">application/x-ms-shortcut</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(uploaded)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\HeidiSQL.lnk)*** of type ***(application/x-ms-shortcut)*** on ***(May 02, 2025 12:40 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr><tr><td class="id column-id has-row-actions column-primary" data-colname="ID">5<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:39 pm</td><td class="action column-action" data-colname="Event">pasted</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\index.php</td><td class="type column-type" data-colname="Type">text/x-php</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(pasted)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\index.php)*** of type ***(text/x-php)*** on ***(May 02, 2025 12:39 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr><tr><td class="id column-id has-row-actions column-primary" data-colname="ID">4<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:39 pm</td><td class="action column-action" data-colname="Event">deleted</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\deletedme.php</td><td class="type column-type" data-colname="Type">text/x-php</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(deleted)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\deletedme.php)*** of type ***(text/x-php)*** on ***(May 02, 2025 12:39 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr><tr><td class="id column-id has-row-actions column-primary" data-colname="ID">3<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:39 pm</td><td class="action column-action" data-colname="Event">updated</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\deletedme.php</td><td class="type column-type" data-colname="Type">text/x-php</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(updated)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\deletedme.php)*** of type ***(text/x-php)*** on ***(May 02, 2025 12:39 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr><tr><td class="id column-id has-row-actions column-primary" data-colname="ID">2<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:39 pm</td><td class="action column-action" data-colname="Event">renamed</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\index copy 1.php</td><td class="type column-type" data-colname="Type">text/x-php</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(renamed)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\index copy 1.php)*** of type ***(text/x-php)*** on ***(May 02, 2025 12:39 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr><tr><td class="id column-id has-row-actions column-primary" data-colname="ID">1<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="user_name column-user_name" data-colname="User">admin<br>john@flywheel.local</td><td class="time column-time" data-colname="Date &amp; Time">May 02, 2025 12:38 pm</td><td class="action column-action" data-colname="Event">duplicated</td><td class="file_path column-file_path" data-colname="File Path">D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\index copy 1.php</td><td class="type column-type" data-colname="Type">text/x-php</td><td class="ip column-ip" data-colname="IP Address">127.0.0.1</td><td class="actions column-actions" data-colname="Actions"><div>
				<a class="afmp-show-details" afmp-details="The User ***(admin)*** with IP Address ***(127.0.0.1)*** has just ***(duplicated)*** the file ***(D:\Local Sites\file-manager-advanced\app\public\wp-content\themes\index copy 1.php)*** of type ***(text/x-php)*** on ***(May 02, 2025 12:38 pm)*** using File Manager on website ***(https://file-manager-advanced.test)***" href="#">
					<span class="dashicons dashicons-visibility"></span>
				</a>
				<a href="#" onclick="return confirm( 'Are you sure you want to delete this file log?' )">
					<span class="dashicons dashicons-trash"></span>
				</a>
			</div></td></tr>	</tbody>

	<tfoot>
	<tr>
		<th scope="col" class="manage-column column-id column-primary sortable asc"><a href="#"><span>ID</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-user_name sortable desc"><a href="https://file-manager-advanced.test/wp-admin/admin.php?page=afmp-file-logs&amp;orderby=user_id&amp;order=asc"><span>User</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort ascending.</span></a></th><th scope="col" class="manage-column column-time sortable asc"><a href="#"><span>Date &amp; Time</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-action sortable asc"><a href="#"><span>Event</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-file_path sortable asc"><a href="#"><span>File Path</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-type sortable asc"><a href="#"><span>Type</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-ip sortable asc"><a href="#"><span>IP Address</span><span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span> <span class="screen-reader-text">Sort descending.</span></a></th><th scope="col" class="manage-column column-actions">Actions</th>	</tr>
	</tfoot>

</table>
		<div class="afma-datatable-header">	<div class="tablenav bottom">

				<div class="alignleft actions bulkactions">
					</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">6 items</span>
<span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
<span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">1</span></span></span>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span></div>
		<br class="clear">
	</div>
		</div></div></div>
HTML;

    }

	/** 
	 * Fma permissions
	 */
	public function fmaPer() {
		$settings = $this->get();
		$user = wp_get_current_user();
		$allowed_fma_user_roles = isset($settings['fma_user_roles']) ? $settings['fma_user_roles'] : array('administrator');

		if(!in_array('administrator', $allowed_fma_user_roles)) {
		$fma_user_roles = array_merge(array('administrator'), $allowed_fma_user_roles);
		} else {
			$fma_user_roles = $allowed_fma_user_roles;
		}

		$checkUserRoleExistance = array_intersect($fma_user_roles, $user->roles);

		if(count($checkUserRoleExistance) > 0 && !in_array('administrator', $checkUserRoleExistance)) {
            $fmaPer = 'read';
		} else {
			$fmaPer = 'manage_options';
		}
		return $fmaPer;
	}
	/**
	 * Fma - Network Permissions
	 */
	public function networkPer() {
		$settings = $this->get();
		$user = wp_get_current_user();
		$allowed_fma_user_roles = isset($settings['fma_user_roles']) ? $settings['fma_user_roles'] : array();

		$fma_user_roles = $allowed_fma_user_roles;

		$checkUserRoleExistance = array_intersect($fma_user_roles, $user->roles);

		if(count($checkUserRoleExistance) > 0 ) {
			if(!in_array('administrator', $checkUserRoleExistance)) {
				$fmaPer = 'read';
			} else {
				$fmaPer = 'manage_options';
			}
		} else {
			$fmaPer = 'manage_network';
		}
		return $fmaPer;	
	}
	/**
	* Diaplying AFM
    */
     public function file_manager_advanced_ui() {
		 $fmaPer = $this->fmaPer();
		 if(current_user_can($fmaPer)) {
		    include('pages/main.php');
		 }
	 }
	/**
	* Settings
    */
    public function file_manager_advanced_controls(){
		if( current_user_can( 'manage_options' ) ) {
		    include( 'pages/controls.php' );
		 }
	}
	/**
	* Shortcode
    */
    public function file_manager_advanced_shortcodes(){
		if(current_user_can('manage_options')) {
		    include('pages/buy_shortcode.php');
		 }
	}
   /**
	* Saving Options
    */
    public function save() {
	   if(isset($_POST['submit']) && wp_verify_nonce( $_POST['_fmaform'], 'fmaform' )) {
		    _e('Saving options, Please wait...','file-manager-advanced');
		   $save = array();
		   $defaultRole = array('administrator');
		   if(is_multisite()) {
			$defaultRole = array();
		   }
		   $public_dir = isset($_POST['public_path']) ? sanitize_text_field($_POST['public_path']) : '';
		   $save['fma_user_roles'] = isset($_POST['fma_user_role']) ? array_map('sanitize_text_field',$_POST['fma_user_role']) : $defaultRole;
		   $save['fma_theme'] = isset($_POST['fma_theme']) ? sanitize_text_field($_POST['fma_theme']) : 'light';
		   $save['fma_locale'] = isset($_POST['fma_locale']) ? sanitize_text_field($_POST['fma_locale']) : 'en';
		   /* Directory Traversal fix @220723 */
		   $save['public_path'] = $this->afm_sanitize_directory($public_dir);
           $save['public_url'] = isset($_POST['public_url']) ? sanitize_text_field($_POST['public_url']) : '';
		   //25122022
		   $save['upload_max_size'] = isset($_POST['upload_max_size']) ? sanitize_text_field($_POST['upload_max_size']) : '0';
		   $save['display_ui_options'] = isset($_POST['display_ui_options']) ? array_map('sanitize_text_field',$_POST['display_ui_options']) : array();
           $save['hide_path'] = isset($_POST['hide_path']) ? sanitize_text_field($_POST['hide_path']) : 0;
		   $save['enable_trash'] = isset($_POST['enable_trash']) ? sanitize_text_field($_POST['enable_trash']) : 0;
		   $save['enable_htaccess'] = isset($_POST['enable_htaccess']) ? sanitize_text_field($_POST['enable_htaccess']) : 0;
		   $save['fma_upload_allow'] = isset($_POST['fma_upload_allow']) ? sanitize_text_field($_POST['fma_upload_allow']) : 'all';
		   $save['fma_cm_theme'] = isset($_POST['fma_cm_theme']) ? sanitize_text_field($_POST['fma_cm_theme']) : 'default';	   
		  $u = update_option('fmaoptions',$save);
		  if($u) {
			  $this->f('?page=file_manager_advanced_controls&status=1');
		  } else {
			  $this->f('?page=file_manager_advanced_controls&status=2');
		  }
	   }
   }
   /**
	* Sanitize directory path
    */
	public function afm_sanitize_directory($path = '') {
        if(!empty($path)) {
			$path = str_replace('..', '', htmlentities(trim($path)));
		}
		return $path;	
	}
   /**
	* Getting Options
    */
   public function get() {
	   return get_option('fmaoptions');
   }
   /**
	* Diplay Notices
    */
   public function notice($type, $message) {
	    if(isset($type) && !empty($type)) {
	     $class = ($type == '1') ? 'updated' : 'error';
         return '<div class="'.$class.' notice">
		  <p>'.$message.'</p>
		  </div>';
		}
   }
   /**
	* Redirection
    */
    public function f($u) {
		$url = esc_url_raw($u);
		wp_register_script( 'fma-redirect-script', '');
		wp_enqueue_script( 'fma-redirect-script' );
		wp_add_inline_script(
		'fma-redirect-script',
		' window.location.href="'.$url.'" ;'
	  );
	}
	public static function shortcodeUpdateNotice() {
		if(class_exists('file_manager_advanced_shortcode')):
			if(defined('fmas_ver')){ 
				if(fmas_ver < '2.4.1') { 
					return '<div class="error notice" style="background: #f7dfdf">
					<p><strong>Advanced File manager shortcode addon update:</strong> You are using version <strong>'.fmas_ver.'</strong> we recommend you to update to latest version. If you did not receive update please download from <a href="https://advancedfilemanager.com/my-account/" target="_blank">my account</a> page.</p>
					</div>';
				}
			} else {
				return '<div class="error notice" style="background: #f7dfdf">
					<p><strong>Advanced File manager shortcode addon update:</strong> You are using old version, we recommend you to update to latest version. If you did not receive update please download from <a href="https://advancedfilemanager.com/my-account/" target="_blank">my account</a> page.</p>
					</div>';
			}
		endif;
	}
	/**
	 * Get User Roles
	 */
	public function wpUserRoles() {
		global $wp_roles;
        return $wp_roles->roles; 
	}
}