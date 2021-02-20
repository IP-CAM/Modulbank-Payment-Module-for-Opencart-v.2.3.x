Payment module for Opencart 2.3
=================================


### Server installation


1. Go to the section "Modules / Extensions", subsection "Installing extensions"
2. Click on the "Upload file" button, select the file with the extension
3. Click on the "Continue" button. If the download fails, you will need to download the module files yourself. Instructions for this are below.
! [Section "Installing extensions"] (https://fpayments.github.io/screenshots/opencart2.3/extension-upload.png)
4. Go to the admin panel, in the "Modules / Extensions" section, the submenu also "Modules / Extensions"
5. Select the type of extension: "Payment"
6. Find the module with the name "Payment via Modulbank" and click on the "+" button next to it.
! [Section "Activating the extension"] (https://fpayments.github.io/screenshots/opencart2.3/extension-activate.png)

### Setting

After installation, you need to set the basic settings of the module:

  * Enter the store ID and secret key, which can be found in Modulbank's personal account
  * Enable or disable test mode if necessary
  * Make sure the settings are set to the correct VAT rate for your store.
! [Section "Settings"] (https://fpayments.github.io/screenshots/opencart2.3/settings.png)

### Manually loading module files

If loading in the standard way fails, the module files can be loaded manually, for this:

1. Connect to the server in any convenient way (FTP, SFTP, etc.)
2. Open the directory on the server where the online store is installed.
3. Open the file with the module on the local computer (this is a zip archive).
4. Copy the contents of the upload folder in the module archive to the site root.

Further configuration is performed in the same way as during normal module installation.


### Installation video demonstration
! [Section "Installing Modulbank extension"] (https://fpayments.github.io/screenshots/opencart2.3/screencast.gif) 
