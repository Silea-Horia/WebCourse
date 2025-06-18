package com.example.library.model;

import java.util.*;

public class Author {
    public int id;
    public String name;
    public List<Document> documentList;
    public List<Movie> movieList;

    public Author(int id, String name, List<Document> documentList, List<Movie> movieList) {
        this.id = id;
        this.name = name;
        this.documentList = documentList;
        this.movieList = movieList;
    }
}
