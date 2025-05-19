package com.example.snakeapp.Controller;

import com.example.snakeapp.model.Authentificator;
import com.example.snakeapp.model.User;
import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.*;
import jakarta.servlet.annotation.*;

import java.io.IOException;

@WebServlet(name = "loginController", value = "/login-controller")
public class LoginController extends HttpServlet {

    public void init() {
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException {
        String username = request.getParameter("username");
        String password = request.getParameter("password");
        RequestDispatcher rd;

        Authentificator authenticator = new Authentificator();
        String result = authenticator.authenticate(username, password);

        if (result.equals("success")) {
            rd = request.getRequestDispatcher("/index.jsp");
            User user = new User(username, password);
            request.setAttribute("user", user);
            HttpSession session = request.getSession();
            session.setAttribute("user", user);

            // HttpSession session = request.getSession();
            // String user = session.getAttribute("user");
            // if (user==null || user.equals("")) {
            //        return;
            // }
        } else {
            rd = request.getRequestDispatcher("/error.jsp");
        }

        rd.forward(request, response);
    }

    public void destroy() {
    }
}
