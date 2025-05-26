package com.example.snakeapp.model;

import java.util.ArrayList;

public class Snake {
    private ArrayList<Cell> cells;
    private String direction;

    public Snake() {
        this.cells = new ArrayList<>();
        this.direction = "up";
    }

    public Snake(String direction) {
        this.cells = new ArrayList<>();
        this.direction = direction;
    }

    public void reset() {
        this.direction = "up";
        this.cells.getFirst().setCol(3);
        this.cells.getFirst().setRow(3);
        this.cells.get(1).setCol(3);
        this.cells.get(1).setRow(4);
        this.cells.get(2).setCol(3);
        this.cells.get(2).setRow(5);
    }

    public void addCell(Cell cell) {
        this.cells.add(cell.getId(), cell);
    }

    public void moveUp() {
        this.direction = "up";

        this.slither();

        Cell head = this.cells.getFirst();
        head.setRow(head.getRow() - 1);
    }

    public void moveDown() {
        this.direction = "down";

        this.slither();

        Cell head = this.cells.getFirst();
        head.setRow(head.getRow() + 1);
    }

    public void moveLeft() {
        this.direction = "left";

        this.slither();

        Cell head = this.cells.getFirst();
        head.setCol(head.getCol() - 1);
    }

    public void moveRight() {
        this.direction = "right";

        this.slither();

        Cell head = this.cells.getFirst();
        head.setCol(head.getCol() + 1);
    }

    private void slither() {
        for (int i = this.cells.size() - 1; i > 0; i--) {
            Cell previousCell = this.cells.get(i - 1);
            Cell cell = this.cells.get(i);

            cell.setRow(previousCell.getRow());
            cell.setCol(previousCell.getCol());
        }
    }

    public Cell[] getCells() {
        return this.cells.toArray(new Cell[this.cells.size()]);
    }

    public String getDirection() {
        return direction;
    }

    public void setDirection(String direction) {
        this.direction = direction;
    }
}
