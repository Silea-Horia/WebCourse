package com.example.snakeapp.model;

public class Cell {
    private int id;
    private String type;
    private int row;
    private int col;

    public Cell(int id, String type, int row, int col) {
        this.id = id;
        this.type = type;
        this.row = row;
        this.col = col;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public int getRow() {
        return row;
    }

    public void setRow(int row) {
        this.row = row;
    }

    public int getCol() {
        return col;
    }

    public void setCol(int col) {
        this.col = col;
    }
}
