<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>add.htm</title>
	</head>
	<body id="add.htm" onload="">
		<script type="text/javascript" charset="utf-8">
			<?php $image_url="foo.png"; ?>
			<?php $delete_url="/foo/delete"; ?>
			var context = window.parent;
			var elm = window.parent.document.getElementById("upload_"+<?php echo $timestamp ?>);
			elm.className = elm.className.replace(/\b(wait|default)\b/g,"")+" done ";
			elm.getElementsByTagName("a")[0].href = "<?php echo $delete_url ?>";
			elm.style.backgroundImage = 'url("<?php echo $image_url; ?>")'
		</script>
	</body>
</html>