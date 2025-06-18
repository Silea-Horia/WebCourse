<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<!DOCTYPE html>
<html>
<head>
  <title>JSP - Hello World</title>
</head>
<body>
  <form action="${pageContext.request.contextPath}/login-controller" method="post">
    Enter author name : <input type="text" name="name"> <BR>
    Enter object id : <input type="text" name="id"> <BR>
    <input type="submit" name="login" value="login"/>
  </form>
</body>
</html>