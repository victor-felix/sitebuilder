<!DOCTYPE html>
<html>
<head>
    <?php echo $this->html->charset() ?>
    <title></title>
</head>
<body id="add.htm">
    <script type="text/javascript" charset="utf-8">
        var context = window.parent;
        var elm = window.parent.document.getElementById("upload_" + <?php echo $timestamp ?>);
        elm.className = elm.className.replace(/\b(wait|default)\b/g,"")+" done ";
        elm.getElementsByTagName("a")[0].href = "<?php echo '/images/delete/' . $image->id ?>";
        var inputs = elm.getElementsByTagName("input");
				for (var i=0; i < inputs.length; i++) {
					var input = inputs[i];
					if (input.type == "text") {
						input.name = input.name.replace("ID", <?php echo $image->id ?>);
						input.id = input.id.replace("ID", <?php echo $image->id ?>);
					};
				};
        elm.style.backgroundImage = 'url("<?php echo $image->link('139x139') ?>")'
    </script>
</body>
</html>
