import { Component, Input, OnInit } from '@angular/core';
import { NgFor, NgIf } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';
import { Category } from '../model/category';
import { response } from 'express';

@Component({
    selector: 'app-products',
    imports: [NgFor, NgIf],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];
    selectedProduct?: Product = undefined;

    categories: Category[] = [];
    selectedCategory?: Category;
    currentPage: number = 1;
    totalPages: number = 1;

    visibleDetails = true;

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getCategories();
    }

    getCategories(): void {
        this.genericService.fetchCategories()
            .subscribe(categories => this.categories = categories);
    }

    onSelect(category: Category): void {
        console.log("selected " + category.name);
        if (this.selectedCategory == category) {
            this.selectedCategory = undefined;
        } else {
            this.selectedCategory = category;
        }
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    getProductsByCategory( page: number, category?: Category): void {
        if (category === undefined) {
            this.products = [];
            return;
        }
        this.genericService.fetchProducts(category, page)
            .subscribe(
                (response) => {
                    this.products = response.products;
                    this.totalPages = response.totalPages;
                }
            );
    }

    selectProduct(product: Product): void {
        this.selectedProduct = product;
    }

    nextPage(): void {
        this.currentPage++;
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    prevPage(): void {
        this.currentPage--;
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    minimize(): void {
        this.visibleDetails = false;
    }
}
