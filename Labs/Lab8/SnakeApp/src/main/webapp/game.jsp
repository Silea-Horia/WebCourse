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
    <button>^</button>
    <button><</button>
    <button>></button>
    <button>v</button>
</body>
</html>
