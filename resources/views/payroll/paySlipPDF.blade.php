<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>
	<b>TESTING</b>
  <?php var_dump($items); ?>
  @foreach($items as $info)
    {{$info->user_id}}
  @endforeach
</body>
</html>