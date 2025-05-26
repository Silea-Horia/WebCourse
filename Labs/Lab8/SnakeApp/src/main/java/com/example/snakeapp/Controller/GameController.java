package com.example.snakeapp.Controller;

import com.example.snakeapp.model.Cell;
import com.example.snakeapp.model.Snake;
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

@WebServlet("/game")
public class GameController extends HttpServlet {
    private Cell[][] board;
    private Snake snake;
    private Statement stmt;
    private User user;

    public void init() {
        this.connect();
        this.createBoard();
    }

    private void createBoard() {
        this.board = new Cell[8][8];
        for (int i = 0; i < 8; i++) {
            for (int j = 0; j < 8; j++) {
                this.board[i][j] = new Cell(0, "board", i, j);
            }
        }
    }

    private void resetState() {
        try {
            stmt.executeUpdate(
                    "update snake " +
                            "set `row` = 2, col = 3 "+
                            "where id = 0");
            stmt.executeUpdate(
                    "update snake " +
                            "set `row` = 3, col = 3 "+
                            "where id = 1");
            stmt.executeUpdate(
                    "update snake " +
                            "set `row` = 4, col = 3 "+
                            "where id = 2");
            stmt.executeUpdate(
                    "update direction " +
                            "set type = 'up' " +
                            "where userId = " + this.user.getId()
            );
        } catch (SQLException e) {
            e.printStackTrace();
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
        this.getStateFromDb();
        request.setAttribute("board", board);
        request.setAttribute("direction", this.snake.getDirection());
        request.getRequestDispatcher("/game.jsp").forward(request, response);
    }

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        if (request.getParameter("up") != null) {
            this.snake.moveUp();
        } else if (request.getParameter("down") != null) {
            this.snake.moveDown();
        } else if (request.getParameter("left") != null) {
            this.snake.moveLeft();
        } else if (request.getParameter("right") != null) {
            this.snake.moveRight();
        }


        // update snake
        this.writeStateToDb();
        this.createBoard();
        this.getStateFromDb();
        request.setAttribute("board", board);
        request.setAttribute("direction", this.snake.getDirection());
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

    private void getStateFromDb() {
        this.snake = new Snake();

        ResultSet rs;
        try {
            rs = stmt.executeQuery(
                        "select * " +
                            "from snake " +
                            "where userId = " + this.user.getId());

            while (rs.next()) {
                Cell snakeCell = new Cell(rs.getInt(1), rs.getString(2), rs.getInt(3), rs.getInt(4));
                this.snake.addCell(snakeCell);
                this.board[snakeCell.getRow()][snakeCell.getCol()] = snakeCell;
            }

            rs = stmt.executeQuery(
                        "select type " +
                            "from direction " +
                            "where userId = " + this.user.getId()
            );

            rs.next();

            this.snake.setDirection(rs.getString("type"));

            rs.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void writeStateToDb() {
        Cell[] cells = this.snake.getCells();

        try {
            for (Cell cell : cells) {
                stmt.executeUpdate(
                        "update snake " +
                                "set `row` = " + cell.getRow() + ", col = " + cell.getCol() + " " +
                                "where id = " + cell.getId());
            }
            stmt.executeUpdate(
                            "update direction " +
                                "set type = '" + this.snake.getDirection() + "' " +
                                "where userId = " + this.user.getId()
            );
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void destroy() {

    }
}
