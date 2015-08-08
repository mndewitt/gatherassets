<?php

class gatherAssets {

	private $imgDir = '';

	/*
	** Constructor.  Establishes the target image directory
	** @param string imgDir
	** @return void
	*/

	function __construct($imgDir) {
		//Do the user a favor and add a trailing slash if they did not add one
		if(substr($imgDir, -1) !== '/') {
			$imgDir .= '/';
		}

		$this->imgDir = $imgDir;
	}

	/*
	** Gathers background images from css/less/sass files
	** @param string pathToFile
	** @return void
	*/

	public function fromCss($pathToFile) {
		//Credit to purcaholic: https://gist.github.com/purcaholic/1341358

		$css = file_get_contents($pathToFile);
		preg_match_all('/url\(([\s])?([\"|\'])?(.*?)([\"|\'])?([\s])?\)/i', $css, $matches);

		// in case of found matches, the multi-dimensional array $matches contains following
		// important entries:
		// $matches[0]  (array)  List containing each string matching the full pattern, e. g. url("images/bg.gif")
		// $matches[3]  (array)  List containing matched url definitions, e. g. images/bg.gif

		if ($matches) {
			foreach($matches[3] as $match) {
				$fileName = substr($match, strrpos($match, '/') + 1);
				$this->copyFromUrl($fileName, $match);
			}
		}
	}

	/*
	** Copies files from remote urls found in the img tags within an html or php file
	** @param string pathToFile
	** @return void
	*/

	public function fromHtml($pathToFile) {
		$html = file_get_contents($pathToFile);
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
		$tags = $doc->getElementsByTagName('img');
		foreach($tags as $t) {
			$remoteUrl = $t->getAttribute('src');
			$fileName = substr($remoteUrl, strrpos($remoteUrl, '/') + 1);
			$this->copyFromUrl($fileName, $remoteUrl);
		}
	}

	/*
	** Copies files from a remote URL to a local directory
	** @param string fileName, string remoteUrl
	** @return void
	*/

	private function copyFromUrl($fileName, $remoteUrl) {
		$newImgPath = $this->imgDir . $fileName;
		copy($remoteUrl, $newImgPath);
		echo $remoteUrl . " has been copied to " . $newImgPath . "<br/>";
	}

	/*
	** Easily clean the target directory if a mistake has been made
	** @return void
	*/

	public function cleanDir() {
		$files = glob($this->imgDir . '*');
		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
				echo "Deleted " . $file . "<br/>";
			}
		}
	}
}