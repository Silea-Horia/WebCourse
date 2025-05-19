package com.example.snakeapp.Controller;

import com.example.snakeapp.model.Cell;
import com.example.snakeapp.model.User;
import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

import java.io.IOException;
import java.sql.*;
import java.util.Arrays;

@WebServlet("/game")
public class GameController extends HttpServlet {
    private Cell[][] board;
    private Statement stmt;
    private User user;

    public void init() {
        this.createBoard();
        this.connect();
    }

    private void createBoard() {
        this.board = new Cell[8][8];
        for (int i = 0; i < 8; i++) {
            for (int j = 0; j < 8; j++) {
                this.board[i][j] = new Cell(0, "board", i, j);
            }
        }
    }

    public void connect() {
        try {
            Connection con = DriverManager.getConnection("jdbc:mysql://localhost:3306/snake_game", "root", "");
            this.stmt = con.createStatement();
        } catch(Exception ex) {
            System.out.println("eroare la connect:"+ex.getMessage());
            ex.printStackTrace();
        }
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        this.verifyUser(request, response);
        this.getSnake();
        request.setAttribute("board", board);
        request.getRequestDispatcher("/game.jsp").forward(request, response);
    }

    private void verifyUser(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        HttpSession session = request.getSession();
        this.user = (User) session.getAttribute("user");
        if (user == null) {
            RequestDispatcher rd = request.getRequestDispatcher("/login.jsp");
            rd.forward(request, response);
        }
    }

    private void getSnake() {
        ResultSet rs;
        try {
            rs = stmt.executeQuery(
                    "select * " +
                            "from snake " +
                        "where userId = " + this.user.getId());

            while (rs.next()) {
                Cell snakeCell = new Cell(rs.getInt(1), rs.getString(2), rs.getInt(3), rs.getInt(4));
                this.board[snakeCell.getRow()][snakeCell.getCol()] = snakeCell;
            }

            rs.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void destroy() {

    }
}
