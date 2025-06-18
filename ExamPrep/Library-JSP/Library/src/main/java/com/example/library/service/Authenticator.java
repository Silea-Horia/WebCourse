package com.example.library.service;

import com.example.library.model.Author;
import com.example.library.model.Document;
import com.example.library.model.Movie;

import java.sql.*;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;

public class Authenticator {
    Connection conn;

    public Authenticator() {
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

    public Author authenticate(String authorName, int objectId) throws SQLException {
        Author result = null;

        String sql = "SELECT a.id, a.documentList, a.movieList " +
                "FROM Authors a " +
                "WHERE a.name = ? " +
                "AND (a.id IN (SELECT a1.id FROM Authors a1 INNER JOIN Documents d on ? = d.id)" +
                "OR a.id IN (SELECT a2.id FROM Authors a2 INNER JOIN Movies m on ? = m.id))";

        PreparedStatement stmt = conn.prepareStatement(sql);

        stmt.setString(1, authorName);
        stmt.setInt(2, objectId);
        stmt.setInt(3, objectId);

        ResultSet rs = stmt.executeQuery();

        if (rs.next()) {
            int authorId = rs.getInt("id");

            List<Integer> documentIdList = Arrays.stream(
                    rs.getString("documentList").split(";"))
                    .map(Integer::parseInt)
                    .collect(Collectors.toCollection(ArrayList::new));

            List<Integer> movieIdList = Arrays.stream(
                            rs.getString("movieList").split(";"))
                    .map(Integer::parseInt)
                    .collect(Collectors.toCollection(ArrayList::new));

            stmt.close();

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

            actualDocuments = actualDocuments
                    .stream()
                    .filter(document -> documentIdList.contains(document.id))
                    .collect(Collectors.toCollection(ArrayList::new));

            actualMovies = actualMovies
                    .stream()
                    .filter(movie -> movieIdList.contains(movie.id))
                    .collect(Collectors.toCollection(ArrayList::new));

            result = new Author(authorId, authorName, actualDocuments, actualMovies);
        }


        return result;
    }
}
