package com.example.library.controller;

import com.example.library.model.*;
import com.example.library.service.*;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;

import javax.xml.crypto.Data;
import java.io.IOException;
import java.sql.SQLException;
import java.util.*;
import java.util.stream.Collectors;

@WebServlet(name = "homeController", value = "/home-controller")
public class HomeController extends HttpServlet {
    public void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        Author user = (Author) request.getSession().getAttribute("user");

        if (request.getParameter("all") != null) {
            List<Object> objects;

            objects = getAll(user);

            request.setAttribute("list", objects);
            request.getRequestDispatcher("/home.jsp").forward(request, response);
        }

        if (request.getParameter("insert") != null) {
            String name = request.getParameter("name");
            String contents = request.getParameter("contents");

            if (addDocument(user, name, contents)) {
                request.setAttribute("message", "Document added!");
            } else {
                request.setAttribute("message", "Document not added!");
            }

            request.getRequestDispatcher("/home.jsp").forward(request, response);
        }

        if (request.getParameter("delete") != null) {
            int id = Integer.parseInt(request.getParameter("id"));

            if (deleteMovie(user, id)) {
                request.setAttribute("message", "Movie deleted!");
            } else {
                request.setAttribute("message", "Movie not deleted!");
            }

            request.getRequestDispatcher("/home.jsp").forward(request, response);
        }
    }

    private List<Object> getAll(Author user) {
        DatabaseService databaseService = new DatabaseService();

        List<Document> documents = new ArrayList<>();
        try {
            documents = databaseService.getDocuments()
                    .stream()
                    .filter(document -> user.documentList
                            .stream()
                            .map(document1 -> document1.id)
                            .toList()
                            .contains(document.id))
                    .collect(Collectors.toCollection(ArrayList::new));
        } catch (SQLException _) {}

        List<Movie> movies = new ArrayList<>();
        try {
            movies = databaseService.getMovies()
                    .stream()
                    .filter(movie -> user.movieList
                            .stream()
                            .map(movie1 -> movie1.id)
                            .toList()
                            .contains(movie.id))
                    .collect(Collectors.toCollection(ArrayList::new));
        } catch (SQLException _) {}

        List<Object> output = new ArrayList<>();

        int i = 0, j = 0;

        while (i < documents.size() && j < movies.size()) {
            if ((i + j) % 2 == 0) {
                output.add(documents.get(i++));
            } else {
                output.add(movies.get(j++));
            }
        }

        while (i < documents.size()) {
            output.add(documents.get(i++));
        }

        while (j < movies.size()) {
            output.add(movies.get(j++));
        }

        return output;
    }

    private boolean addDocument(Author user, String name, String contents) {
        DatabaseService databaseService = new DatabaseService();
        int id = -1;
        Document document = new Document(id, name, contents);
        try {
            id = databaseService.addDocument(user, document);
        } catch (SQLException e) {
            return false;
        }
        document.id = id;
        user.documentList.add(document);
        return true;
    }

    private boolean deleteMovie(Author user, int id) {
        DatabaseService databaseService = new DatabaseService();
        try {
            databaseService.deleteMovie(id);
        } catch (SQLException e) {
            return false;
        }

        user.movieList = user.movieList
                .stream()
                .filter(movie -> movie.id != id)
                .collect(Collectors.toCollection(ArrayList::new));
        return true;
    }
}
