<?
	$content = file_get_contents('php://input');

	$handle = fopen('c2blog.txt', 'w');

	fwrite($handle, $content);

	fclose($handle);
?>