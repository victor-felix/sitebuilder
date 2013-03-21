<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title></title>
</head>
<body id="add.htm">
	<script type="text/javascript" charset="utf-8">
		var context = window.parent;
		var elm = window.parent.document.getElementById("upload_" + <?php echo $timestamp ?>);
		<?php if($image): ?>
			elm.className = elm.className.replace(/\b(wait|default)\b/g, "") + " done ";
			elm.getElementsByTagName("a")[0].href = "<?php echo '/images/delete/' . $image->id ?>";
			var inputs = elm.getElementsByTagName("input");
			for (var i=0; i < inputs.length; i++) {
				var input = inputs[i];
				if (input.type == "text") {
					input.name = input.name.replace("ID", <?php echo $image->id ?>);
					input.id = input.id.replace("ID", <?php echo $image->id ?>);
				}
			}
			elm.style.backgroundImage = 'url("<?php echo $image->link('139x139') ?>")';
		<?php else: ?>
			context.$(elm).remove();
			alert("sorry, we had a upload problem");
		<?php endif ?>
	</script>

</body>
</html>
