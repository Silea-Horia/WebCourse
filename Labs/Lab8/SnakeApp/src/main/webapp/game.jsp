<%@ page import="com.example.snakeapp.model.Cell" %>
<%@ page import="java.util.Objects" %>
<%@ page import="com.example.snakeapp.model.User" %><%--
  Created by IntelliJ IDEA.
  User: horia
  Date: 19.05.2025
  Time: 08:50
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<html>
<head>
    <title>Snake Game</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <p>User: <%=((User)session.getAttribute("user")).getUsername()%></p>
    <table>
        <tbody>
            <%
                Cell[][] board = (Cell[][]) request.getAttribute("board");

                for (Cell[] row : board) {
                    %><tr><%

                    for (Cell cell : row) {
                        String color = "white";

                        if (Objects.equals(cell.getType(), "head")) {
                            color = "lawngreen";
                        } else if (Objects.equals(cell.getType(), "body")) {
                            color = "forestgreen";
                        }

                        %><td style="background: <%=color%>;"></td><%
                    }

                    %></tr><%
                }
            %>
        </tbody>
    </table>
    <form action="${pageContext.request.contextPath}/game" method="get">
        <%
            String direction = (String) request.getAttribute("direction");
            String upDisable = "";
            String downDisable = "";
            String leftDisable = "";
            String rightDisable = "";
            if(Objects.equals(direction, "up")) {
                downDisable = "disabled";
            }
            if(Objects.equals(direction, "down")) {
                upDisable = "disabled";
            }
            if(Objects.equals(direction, "left")) {
                rightDisable = "disabled";
            }
            if(Objects.equals(direction, "right")) {
                leftDisable = "disabled";
            }

        %>
        <input type="submit" name="up" value="^" <%=upDisable%>/>
        <input type="submit" name="down" value="v" <%=downDisable%>/>
        <input type="submit" name="left" value="<" <%=leftDisable%>/>
        <input type="submit" name="right" value=">" <%=rightDisable%>/>
    </form>
</body>
</html>
