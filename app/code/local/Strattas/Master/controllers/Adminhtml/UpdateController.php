<?php


class Strattas_Master_Adminhtml_UpdateController extends Mage_Adminhtml_Controller_action


{

    public function runUpdateAction(){
        $extensionName = $this->getRequest()->getParam('extension_name');
        $info = Strattas_Master_Block_Jsinit::getExtensionInfo($extensionName);

        $download_url = $info->getDownloadUrl();
        $parts = explode("/",$download_url);
        $download_filename = $parts[count($parts)-1];
        echo "<h3>Running Extension Update for {$extensionName}</h3><br><strong>Download URL:</strong> {$download_url}<br><strong>Download Filename:</strong> {$download_filename}<br>\n";

        if(!class_exists('ZipArchive')) {
            echo "ERROR: PHP-extension zip (class ZipArchive) is not installed<br>\n";
            exit;
        }

        $tmpdir = Mage::getConfig()->getOptions()->getTmpDir();
echo "Writing To Dir: {$tmpdir}<br>\n";
        if(!is_writable($tmpdir)) {
            echo 'ERROR: '.$tmpdir.' is not writable';
            exit;
        }

        $data = $this->_getRemote($download_url);
        if(empty($data)) {
            echo 'ERROR: Downloaded update-file is empty';
            exit;
        }
        $tmpfile = $tmpdir.DS.$download_filename;
        file_put_contents($tmpfile, $data);
        ini_set('error_reporting', 1);

        $zip = new ZipArchive();
        if($zip->open($tmpfile) === true) {
            $root = Mage::getBaseDir();
echo "Extracting To: {$root}<br>\n";
            $result = $zip->extractTo($root);
if(!$result) {
echo "<strong>Failed to extract to:</strong> {$root}<br>\n";

}
            $zip->close();
        } else {
            echo 'ERROR: Failed to extract the upgrade-archive';
            exit;
        }

        // Reset the configuration cache
        Mage::getConfig()->removeCache();
        echo "Extension Update Complete! Please logout and log back in to complete upgrade";
        $this->getResponse()->setBody("Done");
        exit;
    }

    private function _getRemote($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $data = curl_exec($ch);
        return $data;
    }


}
