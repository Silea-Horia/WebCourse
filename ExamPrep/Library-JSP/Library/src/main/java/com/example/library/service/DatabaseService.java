package com.example.library.service;

import com.example.library.model.Author;
import com.example.library.model.Document;
import com.example.library.model.Movie;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.*;
import java.util.*;

public class DatabaseService {
    Connection conn;

    public DatabaseService() {
        this.connect();
    }

    private void connect() {
        try {
            String url = "jdbc:mysql://localhost:3306/library";
            String user = "root";
            String password = "";

            this.conn = DriverManager.getConnection(url, user, password);
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
    }

    public List<Movie> getMovies() throws SQLException {
        String sql;
        PreparedStatement stmt;
        ResultSet rs;

        sql = "SELECT * FROM Movies";
        stmt = conn.prepareStatement(sql);
        rs = stmt.executeQuery();
        List<Movie> actualMovies = new ArrayList<>();
        while (rs.next()) {
            int mId = rs.getInt("id");
            String mTitle = rs.getString("title");
            int mDuration = rs.getInt("duration");
            Movie Movie = new Movie(mId, mTitle, mDuration);
            actualMovies.add(Movie);
        }

        stmt.close();

        return actualMovies;
    }

    public List<Document> getDocuments() throws SQLException {
        String sql;
        PreparedStatement stmt;
        ResultSet rs;

        sql = "SELECT * FROM Documents";
        stmt = conn.prepareStatement(sql);
        rs = stmt.executeQuery();
        List<Document> actualDocuments = new ArrayList<>();
        while (rs.next()) {
            int dId = rs.getInt("id");
            String dName = rs.getString("name");
            String dContent = rs.getString("contents");
            Document document = new Document(dId, dName, dContent);
            actualDocuments.add(document);
        }
        stmt.close();

        return actualDocuments;
    }

    public int addDocument(Author author, Document document) throws SQLException {
        String sql = "insert into Documents (name, contents) values (?, ?)";
        PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);
        stmt.setString(1, document.name);
        stmt.setString(2, document.contents);
        stmt.executeUpdate();
        ResultSet rs = stmt.getGeneratedKeys();
        rs.next();
        int id = rs.getInt(1);
        stmt.close();

        sql = "update authors " +
                "set documentList = CONCAT(documentList, ?, ';')" +
                "where id = ?";
        stmt = conn.prepareStatement(sql);
        stmt.setString(1, String.valueOf(id));
        stmt.setInt(2, author.id);
        stmt.executeUpdate();
        stmt.close();

        return id;
    }

    public void deleteMovie(int id) throws SQLException {
        String sql = "delete from movies where id = ?";
        PreparedStatement stmt = conn.prepareStatement(sql);
        stmt.setInt(1, id);
        stmt.executeUpdate();
        stmt.close();
    }
}
