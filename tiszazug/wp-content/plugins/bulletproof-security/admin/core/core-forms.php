<?php
// Direct calls to this file are Forbidden when core files are not present 
if ( ! current_user_can('manage_options') ) { 
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
	
// Form: Root BulletProof Mode and Default Mode - copy and rename htaccess files to root folder
if ( isset( $_POST['Submit-Secure-Root'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_root_copy' );
	
	$options = get_option('bulletproof_security_options_autolock');
	$DefaultHtaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/default.htaccess';
	$RootHtaccess = ABSPATH . '.htaccess';
	$SecureHtaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/secure.htaccess';
	$permsRootHtaccess = @substr(sprintf('%o', fileperms($RootHtaccess)), -4);
	$sapi_type = php_sapi_name();	

	if ( $_POST['bpsecureroot'] == 'bulletproof' ) { 
		
		if ( @substr($sapi_type, 0, 6) != 'apache' && @$permsRootHtaccess != '0666' || @$permsRootHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($RootHtaccess, 0644);
		}		
		
		if ( ! copy($SecureHtaccess, $RootHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to activate Root Folder BulletProof Mode protection. Your website is NOT protected.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
			
			if ( @$permsRootHtaccess == '0644' && @substr($sapi_type, 0, 6) != 'apache' && $options['bps_root_htaccess_autolock'] != 'Off' || $options['bps_root_htaccess_autolock'] == 'On' ) {			
				@chmod($RootHtaccess, 0404);
			}
		
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Root Folder BulletProof Mode protection activated. Your website is now protected.', 'bulletproof-security').'</strong></font>';
			echo $text;
    		echo $bps_bottomDiv;

			// Save the Setup Wizard DB option only once if it does not already exist
			// A manual new setup of BPS was chosen instead of using the Setup Wizard
			// A BPS upgrade updates the value to: upgrade once if the option does not already exist.
			$bps_setup_wizard = 'bulletproof_security_options_wizard_free';
			$BPS_Wizard = array( 'bps_wizard_free' => 'manual' );	
	
			if ( ! get_option( $bps_setup_wizard ) ) {	
		
				foreach( $BPS_Wizard as $key => $value ) {
					update_option('bulletproof_security_options_wizard_free', $BPS_Wizard);
				}
			}		
		}
	}
	elseif ( $_POST['bpsecureroot'] == 'default' ) {

		if ( @substr($sapi_type, 0, 6) != 'apache' || @$permsRootHtaccess != '0666' || @$permsRootHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($RootHtaccess, 0644);
		}

		if ( !copy($DefaultHtaccess, $RootHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to activate Root Folder BulletProof Mode (Default Mode). Unable to Copy the default.htaccess file to your root folder.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {

			if ( @$permsRootHtaccess == '0644' && @substr($sapi_type, 0, 6) != 'apache' && $options['bps_root_htaccess_autolock'] != 'Off' || $options['bps_root_htaccess_autolock'] == 'On' ) {
				@chmod($RootHtaccess, 0404);
			}
			
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Reminder Warning: Root Folder BulletProof Mode (Default Mode) is activated. Your root folder is not protected.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
	}
}

// Form: wp-admin BulletProof Mode - copy and rename htaccess file to wp-admin folder
// Do String Replacements for Custom Code AFTER new .htaccess file has been copied to wp-admin
if ( isset( $_POST['Submit-Secure-wpadmin'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_wpadmin_copy' );
	
	$BPS_wpadmin_Options = get_option('bulletproof_security_options_htaccess_res');
	
	if ( $BPS_wpadmin_Options['bps_wpadmin_restriction'] == 'disabled' ) {
		echo $bps_topDiv;
		$text = '<font color="red"><strong>'.__('wp-admin Folder BulletProof Mode was not activated. Either it is disabled on the Security Modes page or you have a Go Daddy Managed WordPress Hosting account. The wp-admin folder is restricted on GDMW hosting account types.', 'bulletproof-security').'</strong></font>';
		echo $text;
   		echo $bps_bottomDiv;		
	return;
	}
	
	$options = get_option('bulletproof_security_options_customcode_WPA');  
	$HtaccessMaster = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
	$wpadminHtaccess = ABSPATH . 'wp-admin/.htaccess';
	$permsHtaccess = @substr(sprintf('%o', fileperms($wpadminHtaccess)), -4);
	$sapi_type = php_sapi_name();	
	$bpsString1 = "# CCWTOP";
	$bpsString2 = "# CCWPF";
	$bpsString3 = '/#\sBEGIN\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES(.*)#\sEND\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES/s';
	$bpsString4 = '/#\sBEGIN\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS(.*)#\sEND\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS/s';
	$bpsReplace1 = htmlspecialchars_decode($options['bps_customcode_one_wpa'], ENT_QUOTES);
	$bpsReplace2 = htmlspecialchars_decode($options['bps_customcode_two_wpa'], ENT_QUOTES);
	$bpsReplace3 = htmlspecialchars_decode($options['bps_customcode_deny_files_wpa'], ENT_QUOTES);	
	$bpsReplace4 = htmlspecialchars_decode($options['bps_customcode_bpsqse_wpa'], ENT_QUOTES);	
	
	if ( $_POST['bpsecurewpadmin'] == 'bulletproof' ) {

		if ( @substr($sapi_type, 0, 6) != 'apache' || @$permsHtaccess != '0666' || @$permsHtaccess != '0777') { // Windows IIS, XAMPP, etc
			@chmod($wpadminHtaccess, 0644);
		}		

		if ( ! copy($HtaccessMaster, $wpadminHtaccess) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to activate wp-admin Folder BulletProof Mode protection. Your wp-admin folder is NOT protected.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
			
		} else {
	
			if ( file_exists($wpadminHtaccess) ) {
				
				if ( @$permsHtaccess != '0666' || @$permsHtaccess != '0777' ) { // Windows IIS, XAMPP, etc
					@chmod($wpadminHtaccess, 0644);
				}				
				
				$bpsBaseContent = file_get_contents($wpadminHtaccess);
		
			if ( $options['bps_customcode_deny_files_wpa'] != '') {        
				$bpsBaseContent = preg_replace('/#\sBEGIN\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES(.*)#\sEND\sBPS\sWPADMIN\sDENY\sACCESS\sTO\sFILES/s', $bpsReplace3, $bpsBaseContent);
			}
			
			if ( $options['bps_customcode_bpsqse_wpa'] != '') {        
				$bpsBaseContent = preg_replace('/#\sBEGIN\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS(.*)#\sEND\sBPSQSE-check\sBPS\sQUERY\sSTRING\sEXPLOITS\sAND\sFILTERS/s', $bpsReplace4, $bpsBaseContent);
			}
				
				$bpsBaseContent = str_replace($bpsString1, $bpsReplace1, $bpsBaseContent);
				$bpsBaseContent = str_replace($bpsString2, $bpsReplace2, $bpsBaseContent);
				
				file_put_contents( $wpadminHtaccess, $bpsBaseContent );

				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('wp-admin Folder BulletProof Mode protection activated. Your wp-admin folder is now protected.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}
		}
	}
	elseif ( $_POST['bpsecurewpadmin'] == 'default' ) {

		@unlink($wpadminHtaccess);
	
	if ( file_exists($wpadminHtaccess) ) {
		echo $bps_topDiv;
		$text = '<font color="red"><strong>'.__('Failed to delete the wp-admin htaccess file! The file does not exist. It may have been deleted or renamed already.', 'bulletproof-security').'</strong></font>';
		echo $text;
   		echo $bps_bottomDiv;
	
	} else {
		
		echo $bps_topDiv;
		$text = '<font color="green"><strong>'.__('wp-admin Folder BulletProof Mode deactivated. The wp-admin htaccess file has been deleted. If you are testing or troubleshooting then be sure to activate wp-admin BulletProof Mode when you are done testing.', 'bulletproof-security').'</strong></font><br><font color="red"><strong>'.__('Your wp-admin folder is no longer protected.', 'bulletproof-security').'</strong></font>';
		echo $text;
		echo $bps_bottomDiv;
	}
	}
}

// Form: BPS Master htaccess folder - copy Deny All htaccess file 
if ( isset( $_POST['Submit-Master-Folder'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_denyall_master' );
	
	$bps_rename_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$deny_all_ifmodule = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all-ifmodule.htaccess';
	$bps_rename_htaccess_renamed = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/.htaccess';
	
	$Apache_Mod_options = get_option('bulletproof_security_options_apache_modules');	

	if ( $_POST['bpssecuremaster'] == 'bulletproof' ) { 

		if ( $Apache_Mod_options['bps_apache_mod_ifmodule'] == 'Yes' ) {
			
			if ( ! copy($deny_all_ifmodule, $bps_rename_htaccess_renamed) ) {
				echo $bps_topDiv;
				$text = '<font color="red"><strong>'.__('Failed to Activate Master htaccess BulletProof Mode. Your BPS Master htaccess folder is NOT Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
   				echo $bps_bottomDiv;
			} else {
				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('Master htaccess BulletProof Mode Activated. Your BPS Master htaccess folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}			
		
		} else {
			
			if ( ! copy($bps_rename_htaccess, $bps_rename_htaccess_renamed) ) {
				echo $bps_topDiv;
				$text = '<font color="red"><strong>'.__('Failed to Activate Master htaccess BulletProof Mode. Your BPS Master htaccess folder is NOT Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
   				echo $bps_bottomDiv;
			} else {
				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('Master htaccess BulletProof Mode Activated. Your BPS Master htaccess folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}			
		}
	}
}

// Form: BPS backup folder - copy Deny All htaccess file 
if ( isset( $_POST['Submit-Backup-Folder'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_denyall_bpsbackup' );
	
	$bps_rename_htaccess_backup = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$deny_all_ifmodule = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all-ifmodule.htaccess';
	$bps_rename_htaccess_backup_online = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	
	$Apache_Mod_options = get_option('bulletproof_security_options_apache_modules');	

	if ( $_POST['bpssecurebackup'] == 'bulletproof' ) { 
		
		if ( $Apache_Mod_options['bps_apache_mod_ifmodule'] == 'Yes' ) {
			
			if ( ! copy($deny_all_ifmodule, $bps_rename_htaccess_backup_online) ) {
				echo $bps_topDiv;
				$text = '<font color="red"><strong>'.__('Failed to Activate BPS Backup BulletProof Mode. Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is NOT Protected with Deny All htaccess folder protection!', 'bulletproof-security').'</strong></font>';
				echo $text;
   				echo $bps_bottomDiv;
			} else {
				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('BPS Backup BulletProof Mode Activated. Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}			
		
		} else {
			
			if ( ! copy($bps_rename_htaccess_backup, $bps_rename_htaccess_backup_online) ) {
				echo $bps_topDiv;
				$text = '<font color="red"><strong>'.__('Failed to Activate BPS Backup BulletProof Mode. Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is NOT Protected with Deny All htaccess folder protection!', 'bulletproof-security').'</strong></font>';
				echo $text;
   				echo $bps_bottomDiv;
			} else {
				echo $bps_topDiv;
				$text = '<font color="green"><strong>'.__('BPS Backup BulletProof Mode Activated. Your BPS /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder is Now Protected with Deny All htaccess folder protection.', 'bulletproof-security').'</strong></font>';
				echo $text;
				echo $bps_bottomDiv;
			}			
		}
	}
}

// Form: Backup htaccess files
if ( isset( $_POST['Submit-Backup-htaccess-Files'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_backup_active_htaccess_files' );
	
	$old_backroot = ABSPATH . '.htaccess';
	$new_backroot = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
	$old_backwpadmin = ABSPATH . 'wp-admin/.htaccess';
	$new_backwpadmin = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';
	
	if ( $_POST['bpsbackuphtaccessfiles'] == 'backup-htaccess-files' ) { 
	
		if ( ! file_exists($old_backroot) ) { 
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('You do not currently have an .htaccess file in your Root folder to backup.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo '</p></div>';
		
		} else {	
		
		if ( ! copy($old_backroot, $new_backroot) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Backup Your Root .htaccess File. File copy function failed. Check the folder permissions for the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder. Folder permissions should be set to 755.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your currently active Root .htaccess file has been backed up successfully.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		}
		}
		
		if ( ! file_exists($old_backwpadmin) ) { 
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('You do not currently have an htaccess file in your wp-admin folder to backup.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
		
		if ( ! copy($old_backwpadmin, $new_backwpadmin) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Backup Your wp-admin htaccess File. File copy function failed. Check the folder permissions for the /', 'bulletproof-security').$bps_wpcontent_dir.__('/bps-backup folder. Folder permissions should be set to 755.', 'bulletproof-security').'</strong></font>';
			echo $text;
			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$text = '<font color="green"><strong>'.__('Your currently active wp-admin htaccess file has been backed up successfully.', 'bulletproof-security').'</strong></font><br>';
			echo $text;
			echo $bps_bottomDiv;
		}
		}
	}
}

// Form: Restore backed up htaccess files
if ( isset( $_POST['Submit-Restore-htaccess-Files'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_restore_active_htaccess_files' );
	
	$old_restoreroot = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
	$new_restoreroot = ABSPATH . '.htaccess';
	$old_restorewpadmin = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';
	$new_restorewpadmin = ABSPATH . 'wp-admin/.htaccess';
	$permsRootHtaccess = @substr(sprintf('%o', fileperms($new_restoreroot)), -4);
	$sapi_type = php_sapi_name();		

	if ( $_POST['bpsrestorehtaccessfiles'] == 'restore-htaccess-files' ) { 
		
		if ( file_exists($old_restoreroot) ) { 
		
			if ( @substr($sapi_type, 0, 6) != 'apache' && @$permsRootHtaccess != '0666' || @$permsRootHtaccess != '0777') { // Windows IIS, XAMPP, etc
				@chmod($new_restoreroot, 0644);
			}	
		
		if ( ! copy($old_restoreroot, $new_restoreroot) ) {
			echo $bps_topDiv;
			echo '<font color="red"><strong>'.__('Failed to Restore Your Root htaccess File. Either you DO NOT currently have a Backed up Root htaccess file or your current active Root htaccess file permissions do not allow the file to be replaced/restored.', 'bulletproof-security').'</strong></font>';
   			echo $bps_bottomDiv;
		
		} else {
			
			if ( @substr($sapi_type, 0, 6) != 'apache' && $options['bps_root_htaccess_autolock'] != 'Off' || $options['bps_root_htaccess_autolock'] == 'On' ) {			
				@chmod($new_restoreroot, 0404);
			}
			
			echo $bps_topDiv;
			$textRoot = '<font color="green"><strong>'.__('Your Root htaccess file has been Restored successfully.', 'bulletproof-security').'</strong></font>';
			echo $textRoot;
			echo $bps_bottomDiv;
		}
		}
		
		if ( file_exists($old_restorewpadmin) ) { 	
		if ( ! copy($old_restorewpadmin, $new_restorewpadmin) ) {
			echo $bps_topDiv;
			$text = '<font color="red"><strong>'.__('Failed to Restore Your wp-admin htaccess File. Either you DO NOT currently have a Backed up wp-admin htaccess file or your current active wp-admin htaccess file permissions do not allow the file to be replaced/restored.', 'bulletproof-security').'</strong></font>';
			echo $text;
   			echo $bps_bottomDiv;
		
		} else {
			
			echo $bps_topDiv;
			$textWpadmin = '<font color="green"><strong>'.__('Your wp-admin htaccess file has been Restored successfully.', 'bulletproof-security').'</strong></font>';
			echo $textWpadmin;
			echo $bps_bottomDiv;
		}
		}
	}
}

?>