<?php

/*
**  Extract assets from a less/css file
*/

//Credit to purcaholic: https://gist.github.com/purcaholic/1341358

$css = file_get_contents('path/to/style.less');

preg_match_all('/url\(([\s])?([\"|\'])?(.*?)([\"|\'])?([\s])?\)/i', $css, $matches);
// in case of found matches, the multi-dimensional array $matches contains following
// important entries:
// $matches[0]  (array)  List containing each string matching the full pattern, e. g. url("images/bg.gif")
// $matches[3]  (array)  List containing matched url definitions, e. g. images/bg.gif
if ($matches) {
	foreach($matches[3] as $match) {
		$id = substr($match, strrpos($match, '/') + 1);
		$new_img_path = 'img/from-legacy-css/' . $id;
		copy('http:' . $match, $new_img_path);
		echo $match . " has been copied to " . $new_img_path . "<br/>";
	}
}

/*
**  Extract assets from an associative array
*/

$features = array(
		array( 
			"url" => "//cdn.mycdn.com/img/76155488/5d9b9ad887dc47b48ffabaaa47ee1ec3.png",
			"title" => "My awesome file name",
		),
		array( 
			"url" => "//cdn.mycdn.com/img/76155488/46eee5697edf48458296480f63ceb6b9.png",
			"title" => "My awesome file name numero dos"
		)
	);

foreach($features as $f) {
	$url = "http:" . $f["url"];
	$title = strpreg_replace('/[ ,]+/', '', $f["title"]);
	$new_img_path = '/img/' . $title . '.png';
	copy($url, $new_img_path);
	echo "copied " . $url . " to " . $new_img_path . "<br/>";
}

/*
**  Extract assets from some html
*/

$html = file_get_contents('path/to/myfile.php');

$doc = new DOMDocument();
@$doc->loadHTML($html);
$tags = $doc->getElementsByTagName('img');
foreach($tags as $t) {
	$url = $t->getAttribute('src');
	$id = substr($url, strrpos($url, '/') + 1);
	$new_img_path = 'img/from-legacy-html/' . $id;
	copy('http:' . $url, $new_img_path);
	echo "copied " . $url . " to " . $new_img_path . "<br/>";
}
