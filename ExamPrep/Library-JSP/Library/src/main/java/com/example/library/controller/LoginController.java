package com.example.library.controller;

import com.example.library.model.Author;
import com.example.library.service.Authenticator;
import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

import java.io.IOException;
import java.sql.SQLException;

@WebServlet(name = "loginController", value = "/login-controller")
public class LoginController extends HttpServlet {

    public void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        RequestDispatcher rd;

        String name = request.getParameter("name");
        int id = Integer.parseInt(request.getParameter("id"));

        Authenticator authenticator = new Authenticator();

        Author user;
        try {
            user = authenticator.authenticate(name, id);
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        if (user != null) {
            HttpSession session = request.getSession();
            session.setAttribute("user", user);
            rd = request.getRequestDispatcher("/home.jsp");
        } else {
            rd = request.getRequestDispatcher("/index.jsp");
        }

        rd.forward(request, response);
    }

    public void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        doPost(request, response);
    }
}
