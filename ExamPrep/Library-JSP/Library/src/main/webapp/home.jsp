<%@ page import="java.util.*" %>
<%@ page import="com.example.library.model.*" %><%--
  Created by IntelliJ IDEA.
  User: horia
  Date: 17.06.2025
  Time: 11:14
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <form action="${pageContext.request.contextPath}/home-controller" method="post">
        <input type="submit" name="all" value="get all">
    </form>
    <h1>List</h1>
    <table>
        <tbody>
            <%
                List<Object> objects = (List<Object>) request.getAttribute("list");
                if (objects != null) {
                    for (Object o : objects) {
                        if (o instanceof Movie) {
                %>
                            <tr>
                                <td>
                                <%=
                                    ((Movie)o).id
                                %>
                                </td>
                                <td>
                                    <%=
                                    ((Movie)o).title
                                    %>
                                </td>
                                <td>
                                    <%=
                                    ((Movie)o).duration
                                    %>
                                </td>
                                <td>
                                    <form action="home-controller" method="post">
                                        <input type="hidden" name="id" value="<%=((Movie)o).id%>">
                                        <input type="submit" name="delete" value="delete">
                                    </form>
                                </td>
                            </tr>
                <%
                        } else {
                %>
                            <tr>
                                <td>
                                    <%=
                                    ((Document)o).id
                                    %>
                                </td>
                                <td>
                                    <%=
                                    ((Document)o).name
                                    %>
                                </td>
                                <td>
                                    <%=
                                    ((Document)o).contents
                                    %>
                                </td>
                            </tr>
                <%
                        }
                    }
                }
            %>
        </tbody>
    </table>
    <form action="home-controller" method="post">
        <label>
            Name:
            <input type="text" name="name" placeholder="name"/>
        </label>
        <label>
            Contents:
            <input type="text" name="contents" placeholder="contents"/>
        </label>
        <input type="submit" name="insert" value="insert">
    </form>
    <p>
        <%=request.getAttribute("message")%>
    </p>
</body>
</html>
