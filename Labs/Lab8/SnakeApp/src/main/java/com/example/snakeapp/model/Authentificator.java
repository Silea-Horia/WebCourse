package com.example.snakeapp.model;

import java.sql.*;

public class Authentificator {
    private Statement stmt;

    public Authentificator() {
        this.connect();
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

    public int authenticate(String username, String password) {
        ResultSet rs;
        int result = 0;
        System.out.println(username+" "+password);
        try {
            rs = stmt.executeQuery(
                    "select * " +
                    "from users " +
                    "where username='" + username + "' and password='" + password + "'");

            if (rs.next()) {
                result = rs.getInt("id");
            }

            rs.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        System.out.println(result);
        return result;
    }
}
