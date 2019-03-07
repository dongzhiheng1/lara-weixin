<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
      <form  action="/admin/wx/chatAll" method="post">
          {{csrf_field()}}
          <textarea style="width:300px;height:200px" name="mes"></textarea>
          <input type="submit" value="send" >
      </form>
</body>
</html>